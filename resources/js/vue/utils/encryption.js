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
 * Derive Key Encryption Key (KEK) from password
 */
export function deriveKEK(password, salt) {
    verifySodiumReady();
    try {
        const passwordBytes = sodium.from_string(password);
        return sodium.crypto_pwhash(
            sodium.crypto_secretbox_KEYBYTES, // 32 bytes
            passwordBytes,
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
 * Full encryption flow
 */
export async function encryptUserData(password, sensitiveData) {
    await initSodium();
    const dek = generateDEK();
    const salt = generateSalt();
    const kek = deriveKEK(password, salt);
    const { ciphertext: encryptedDek, nonce: dekNonce } = encryptDEK(dek, kek);
    const { ciphertext: profileCiphertext, nonce: profileNonce } = encryptData(JSON.stringify(sensitiveData), dek);
    return {
        encrypted_dek: bytesToBase64(encryptedDek),
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
