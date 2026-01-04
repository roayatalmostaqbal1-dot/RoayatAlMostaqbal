import sodium from 'libsodium-wrappers-sumo';

// Track if sodium has been initialized
let sodiumReady = false;

/**
 * Initialize libsodium
 */
export async function initSodium() {
    if (sodiumReady) return;
    try {
        await sodium.ready;
        sodiumReady = true;
    } catch (error) {
        console.error('Failed to initialize libsodium:', error);
        throw new Error('Encryption library failed to initialize. Please refresh the page.');
    }
}

/**
 * Verify sodium is initialized
 */
function verifySodiumReady() {
    if (!sodiumReady) throw new Error('Encryption library not initialized. Call initSodium() first.');
}

/**
 * Generate random Data Encryption Key (DEK)
 */
export function generateDEK() {
    verifySodiumReady();
    return sodium.randombytes_buf(sodium.crypto_secretbox_KEYBYTES);
}

/**
 * Generate random salt
 */
export function generateSalt() {
    verifySodiumReady();
    return sodium.randombytes_buf(sodium.crypto_pwhash_SALTBYTES);
}

/**
 * Derive Key Encryption Key (KEK) from password or recovery key
 */
export function deriveKEK(secret, salt) {
    verifySodiumReady();
    try {
        const secretBytes = typeof secret === 'string' ? sodium.from_string(secret) : secret;
        return sodium.crypto_pwhash(
            sodium.crypto_secretbox_KEYBYTES, // 32 bytes
            secretBytes,
            salt,
            sodium.crypto_pwhash_OPSLIMIT_MODERATE,
            sodium.crypto_pwhash_MEMLIMIT_MODERATE,
            sodium.crypto_pwhash_ALG_ARGON2ID13
        );
    } catch (error) {
        console.error('Key derivation failed:', error);
        throw new Error('Failed to derive encryption key. Please try again.');
    }
}

/**
 * Encrypt DEK with KEK
 */
export function encryptDEK(dek, kek) {
    verifySodiumReady();
    try {
        const nonce = sodium.randombytes_buf(sodium.crypto_aead_xchacha20poly1305_ietf_NPUBBYTES);
        const ciphertext = sodium.crypto_aead_xchacha20poly1305_ietf_encrypt(
            dek,
            null,
            null,
            nonce,
            kek
        );
        return { ciphertext, nonce };
    } catch (error) {
        console.error('DEK encryption failed:', error);
        throw new Error('Failed to encrypt DEK.');
    }
}

/**
 * Decrypt DEK with KEK
 */
export function decryptDEK(ciphertext, nonce, kek) {
    verifySodiumReady();
    try {
        return sodium.crypto_aead_xchacha20poly1305_ietf_decrypt(
            null,
            ciphertext,
            null,
            nonce,
            kek
        );
    } catch (error) {
        console.error('DEK decryption failed:', error);
        throw new Error('Failed to decrypt DEK. Wrong password or corrupted data.');
    }
}

/**
 * Encrypt sensitive data with DEK
 */
export function encryptData(plaintext, dek) {
    verifySodiumReady();
    try {
        const plaintextBytes = sodium.from_string(plaintext);
        const nonce = sodium.randombytes_buf(sodium.crypto_aead_xchacha20poly1305_ietf_NPUBBYTES);
        const ciphertext = sodium.crypto_aead_xchacha20poly1305_ietf_encrypt(
            plaintextBytes,
            null,
            null,
            nonce,
            dek
        );
        return { ciphertext, nonce };
    } catch (error) {
        console.error('Data encryption failed:', error);
        throw new Error('Failed to encrypt data.');
    }
}

/**
 * Decrypt sensitive data with DEK
 */
export function decryptData(ciphertext, nonce, dek) {
    verifySodiumReady();
    try {
        const plaintextBytes = sodium.crypto_aead_xchacha20poly1305_ietf_decrypt(
            null,
            ciphertext,
            null,
            nonce,
            dek
        );
        return sodium.to_string(plaintextBytes);
    } catch (error) {
        console.error('Data decryption failed:', error);
        throw new Error('Failed to decrypt data. Corrupted or invalid data.');
    }
}

/**
 * Convert Uint8Array <-> Base64
 */
export function bytesToBase64(bytes) { verifySodiumReady(); return sodium.to_base64(bytes); }
export function base64ToBytes(base64) { verifySodiumReady(); return sodium.from_base64(base64); }

/**
 * Generate a random high-entropy recovery key (formatted as 4-4-4nd groups for readability)
 */
export function generateRecoveryKey() {
    verifySodiumReady();
    const entropy = sodium.randombytes_buf(16); // 128 bits of entropy
    const base32 = sodium.to_base64(entropy).replace(/[+/=]/g, '').toUpperCase().substring(0, 24);
    return `REC-${base32.match(/.{1,4}/g).join('-')}`;
}

/**
 * Encrypt DEK with Recovery Key
 */
export function encryptDEKWithRecoveryKey(dek, recoveryKey) {
    verifySodiumReady();
    const salt = generateSalt();
    const kek = deriveKEK(recoveryKey, salt);
    const { ciphertext, nonce } = encryptDEK(dek, kek);
    return { ciphertext: bytesToBase64(ciphertext), salt: bytesToBase64(salt), nonce: bytesToBase64(nonce) };
}

/**
 * Decrypt DEK with Recovery Key
 */
export function decryptDEKWithRecoveryKey(encryptedDek, salt, nonce, recoveryKey) {
    verifySodiumReady();
    const saltBytes = base64ToBytes(salt);
    const nonceBytes = base64ToBytes(nonce);
    const encryptedDekBytes = base64ToBytes(encryptedDek);
    const kek = deriveKEK(recoveryKey, saltBytes);
    return decryptDEK(encryptedDekBytes, nonceBytes, kek);
}

/**
 * Encrypt DEK with server's master key (for recovery by admin/server)
 */
export async function encryptDEKWithMasterKey(dek, masterKeyPublicKey) {
    await initSodium();
    verifySodiumReady();
    try {
        // If we have a public key, use asymmetric encryption
        if (masterKeyPublicKey) {
            const publicKeyBytes = base64ToBytes(masterKeyPublicKey);
            const dekBytes = dek;

            // Use box_seal for anonymous encryption (no need for nonce)
            const sealed = sodium.crypto_box_seal(dekBytes, publicKeyBytes);
            return bytesToBase64(sealed);
        } else {
            // Fallback: if no public key, we'll encrypt on server side
            return null;
        }
    } catch (error) {
        console.error('DEK encryption with master key failed:', error);
        throw new Error('Failed to encrypt DEK with master key.');
    }
}

/**
 * Full encryption flow with triple encryption (password + recovery key + master key)
 */
export async function encryptUserData(password, sensitiveData, masterKeyPublicKey = null, recoveryKey = null) {
    await initSodium();
    const dek = generateDEK();
    const salt = generateSalt();
    const kek = deriveKEK(password, salt);

    // 1. Encrypt DEK with user's password (for normal access)
    const { ciphertext: encryptedDek, nonce: dekNonce } = encryptDEK(dek, kek);

    // 2. Encrypt DEK with recovery key (for self-recovery)
    let recoveryData = { encrypted_dek_recovery: null, dek_salt_recovery: null, dek_nonce_recovery: null };
    if (recoveryKey) {
        const { ciphertext, salt: rSalt, nonce: rNonce } = encryptDEKWithRecoveryKey(dek, recoveryKey);
        recoveryData = {
            encrypted_dek_recovery: ciphertext,
            dek_salt_recovery: rSalt,
            dek_nonce_recovery: rNonce
        };
    }

    // 3. Encrypt DEK with server's master key (for admin recovery)
    let encryptedDekServer = null;
    if (masterKeyPublicKey) {
        encryptedDekServer = await encryptDEKWithMasterKey(dek, masterKeyPublicKey);
    }

    // 4. Encrypt the actual data with DEK
    const { ciphertext: profileCiphertext, nonce: profileNonce } = encryptData(JSON.stringify(sensitiveData), dek);

    return {
        encrypted_dek: bytesToBase64(encryptedDek),
        encrypted_dek_server: encryptedDekServer,
        ...recoveryData,
        dek_salt: bytesToBase64(salt),
        dek_nonce: bytesToBase64(dekNonce),
        profile_ciphertext: bytesToBase64(profileCiphertext),
        profile_nonce: bytesToBase64(profileNonce)
    };
}

/**
 * Full decryption flow
 */
export async function decryptUserData(password, dekSalt, encryptedDek, dekNonce, profileCiphertext, profileNonce) {
    await initSodium();
    try {
        const salt = base64ToBytes(dekSalt);
        const encryptedDekBytes = base64ToBytes(encryptedDek);
        const dekNonceBytes = base64ToBytes(dekNonce);
        const profileCiphertextBytes = base64ToBytes(profileCiphertext);
        const profileNonceBytes = base64ToBytes(profileNonce);
        const kek = deriveKEK(password, salt);
        const dek = decryptDEK(encryptedDekBytes, dekNonceBytes, kek);
        const decryptedJson = decryptData(profileCiphertextBytes, profileNonceBytes, dek);
        return JSON.parse(decryptedJson);
    } catch (error) {
        console.error(error);
        throw new Error('Failed to decrypt user data.');
    }
}

/**
 * Clear sensitive data from memory
 */
export function clearSensitiveData(data) {
    if (!sodiumReady || !data) return;
    try { sodium.memzero(data); } catch (e) { console.error('Failed to clear sensitive data:', e); }
}
