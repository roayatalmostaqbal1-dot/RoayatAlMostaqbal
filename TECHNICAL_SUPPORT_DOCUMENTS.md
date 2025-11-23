# Technical Support Documents
## RoayatAlMostaqbal - Detailed Architecture, Diagrams, and Use Cases

**Document Purpose:** Comprehensive technical documentation with architecture diagrams, data flow examples, and comparative analysis.

**Date:** November 2025
**Status:** Patent Examination Support Document

---

## PART 1: SYSTEM ARCHITECTURE DIAGRAMS

### 1.1 Overall System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     RoayatAlMostaqbal System                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────┐         ┌──────────────────┐              │
│  │  Client Device   │         │   Web Browser    │              │
│  │  (Vue.js App)    │◄───────►│  (Blade Views)   │              │
│  └────────┬─────────┘         └────────┬─────────┘              │
│           │                            │                         │
│           │ HTTPS                      │ HTTPS                   │
│           │                            │                         │
│  ┌────────▼────────────────────────────▼─────────┐              │
│  │         Laravel API Server (v11)              │              │
│  ├──────────────────────────────────────────────┤              │
│  │  ┌─────────────────────────────────────────┐ │              │
│  │  │  Authentication Layer (Passport OAuth2) │ │              │
│  │  │  - Login/Register                       │ │              │
│  │  │  - 2FA Verification (TOTP)              │ │              │
│  │  │  - Token Management                     │ │              │
│  │  └─────────────────────────────────────────┘ │              │
│  │                                               │              │
│  │  ┌─────────────────────────────────────────┐ │              │
│  │  │  Encryption Layer                       │ │              │
│  │  │  - CSE Data Handler                     │ │              │
│  │  │  - CSKE Credential Handler              │ │              │
│  │  │  - Key Management                       │ │              │
│  │  └─────────────────────────────────────────┘ │              │
│  │                                               │              │
│  │  ┌─────────────────────────────────────────┐ │              │
│  │  │  Authorization Layer (RBAC)             │ │              │
│  │  │  - Role-Based Access Control            │ │              │
│  │  │  - Permission Verification              │ │              │
│  │  │  - Audit Logging                        │ │              │
│  │  └─────────────────────────────────────────┘ │              │
│  └──────────────────────────────────────────────┘              │
│           │                                                      │
│           │ Encrypted Data                                       │
│           │                                                      │
│  ┌────────▼──────────────────────────────────┐                 │
│  │      Database (MySQL/PostgreSQL)          │                 │
│  │  ┌──────────────────────────────────────┐ │                 │
│  │  │ Users Table                          │ │                 │
│  │  │ - id, email, password_hash           │ │                 │
│  │  └──────────────────────────────────────┘ │                 │
│  │  ┌──────────────────────────────────────┐ │                 │
│  │  │ UserTwoFactorAuth Table              │ │                 │
│  │  │ - encrypted_secret                   │ │                 │
│  │  │ - encrypted_recovery_codes           │ │                 │
│  │  └──────────────────────────────────────┘ │                 │
│  │  ┌──────────────────────────────────────┐ │                 │
│  │  │ EncryptedUserData Table              │ │                 │
│  │  │ - encrypted_dek                      │ │                 │
│  │  │ - profile_ciphertext                 │ │                 │
│  │  └──────────────────────────────────────┘ │                 │
│  └───────────────────────────────────────────┘                 │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## PART 2: DATA FLOW DIAGRAMS

### 2.1 User Registration Flow

```
User Input (Email, Password)
        │
        ▼
┌──────────────────────────┐
│ Client Validation        │
│ - Email format check     │
│ - Password strength      │
└──────────────────────────┘
        │
        ▼
┌──────────────────────────┐
│ Hash Password (bcrypt)   │
│ - Client-side prep       │
└──────────────────────────┘
        │
        ▼ HTTPS POST
┌──────────────────────────┐
│ Server Receives Data     │
│ - Validate input         │
│ - Check email uniqueness │
└──────────────────────────┘
        │
        ▼
┌──────────────────────────┐
│ Create User Record       │
│ - Store hashed password  │
│ - Generate user ID       │
└──────────────────────────┘
        │
        ▼
┌──────────────────────────┐
│ Return Success Response  │
│ - User ID                │
│ - Confirmation message   │
└──────────────────────────┘
```

### 2.2 Encryption Flow (CSE for User Data)

```
User Profile Data (JSON)
        │
        ▼
┌──────────────────────────────────┐
│ Client-Side Encryption           │
│ 1. Generate DEK (Data Encryption │
│    Key) locally                  │
│ 2. Derive key from password +    │
│    salt using HKDF              │
└──────────────────────────────────┘
        │
        ▼
┌──────────────────────────────────┐
│ Encrypt Profile Data             │
│ - Algorithm: AES-256             │
│ - Mode: GCM                      │
│ - Generate nonce                 │
│ - Output: ciphertext             │
└──────────────────────────────────┘
        │
        ▼
┌──────────────────────────────────┐
│ Prepare Payload                  │
│ {                                │
│   encrypted_dek: "...",          │
│   dek_salt: "...",               │
│   dek_nonce: "...",              │
│   profile_ciphertext: "...",     │
│   profile_nonce: "..."           │
│ }                                │
└──────────────────────────────────┘
        │
        ▼ HTTPS POST
┌──────────────────────────────────┐
│ Server Receives Encrypted Data   │
│ - Validate structure             │
│ - Store in database              │
│ - No decryption on server        │
└──────────────────────────────────┘
```

### 2.3 Two-Factor Authentication Flow

```
User Login (Email + Password)
        │
        ▼
┌──────────────────────────┐
│ Verify Credentials       │
│ - Check email exists     │
│ - Verify password hash   │
└──────────────────────────┘
        │
        ▼
┌──────────────────────────┐
│ Check 2FA Enabled        │
│ - Query UserTwoFactorAuth│
│ - Check is_enabled flag  │
└──────────────────────────┘
        │
        ├─ NO ──► Issue Token ──► Return Success
        │
        └─ YES ──┐
                 ▼
        ┌──────────────────────────┐
        │ Request 2FA Code         │
        │ - Return user_id         │
        │ - Request TOTP code      │
        └──────────────────────────┘
                 │
                 ▼
        ┌──────────────────────────┐
        │ User Enters TOTP Code    │
        │ (from authenticator app) │
        └──────────────────────────┘
                 │
                 ▼ HTTPS POST
        ┌──────────────────────────┐
        │ Server Verifies Code     │
        │ 1. Decrypt secret        │
        │ 2. Generate expected code│
        │ 3. Compare with input    │
        └──────────────────────────┘
                 │
        ┌────────┴────────┐
        │                 │
        ▼                 ▼
    VALID            INVALID
        │                 │
        ▼                 ▼
   Issue Token      Check Recovery
        │            Codes
        │                 │
        │            ┌────┴────┐
        │            │          │
        │            ▼          ▼
        │         VALID      INVALID
        │            │          │
        │            ▼          ▼
        │       Issue Token   Reject
        │            │
        └────────┬───┘
                 ▼
        Return Success + Token
```

---

## PART 3: PRACTICAL USE CASE EXAMPLES

### 3.1 Use Case: New User Registration with 2FA Setup

**Scenario:** User registers and enables 2FA

**Steps:**

1. **Registration Phase**
   - User enters: email, password, name
   - Client validates input
   - Client hashes password
   - Server creates user record
   - Response: User ID, confirmation

2. **2FA Setup Phase**
   - User requests 2FA setup
   - Server generates TOTP secret
   - Server encrypts secret with MEK
   - Server generates QR code
   - Client displays QR code
   - User scans with authenticator app

3. **2FA Confirmation Phase**
   - User enters 6-digit code from app
   - Server verifies code against secret
   - Server generates recovery codes
   - Server encrypts recovery codes
   - Server stores encrypted codes
   - Response: 2FA enabled

4. **First Login with 2FA**
   - User enters email + password
   - Server verifies credentials
   - Server detects 2FA enabled
   - Server requests TOTP code
   - User enters code from app
   - Server verifies code
   - Server issues OAuth2 token
   - User logged in successfully

### 3.2 Use Case: User Updates Encrypted Profile

**Scenario:** User updates profile information

**Steps:**

1. **Client-Side Preparation**
   - User enters new profile data
   - Client generates new DEK
   - Client derives key from password + salt
   - Client encrypts profile data with DEK
   - Client encrypts DEK with server's public key

2. **Transmission**
   - Client sends encrypted payload via HTTPS
   - Payload includes:
     - encrypted_dek
     - dek_salt
     - dek_nonce
     - profile_ciphertext
     - profile_nonce

3. **Server-Side Storage**
   - Server receives encrypted payload
   - Server validates structure
   - Server stores in EncryptedUserData table
   - Server does NOT decrypt
   - Response: Success confirmation

4. **Data Retrieval**
   - User requests profile data
   - Server returns encrypted payload
   - Client receives encrypted data
   - Client derives DEK from password + salt
   - Client decrypts profile data
   - User sees decrypted profile

### 3.3 Use Case: Admin Views Encrypted User Data

**Scenario:** Admin needs to support user

**Steps:**

1. **Admin Request**
   - Admin logs in with admin credentials
   - Admin navigates to user support section
   - Admin searches for user by email
   - Admin requests user's encrypted data

2. **Authorization Check**
   - Server verifies admin role
   - Server checks admin permissions
   - Server verifies user exists
   - Server logs access attempt

3. **Data Retrieval**
   - Server retrieves encrypted data
   - Server returns encrypted payload
   - Admin sees encrypted data (cannot read)
   - Admin can see metadata only

4. **Audit Trail**
   - Server logs: admin_id, user_id, timestamp
   - Server logs: action (view_encrypted_data)
   - Server logs: IP address
   - Audit trail stored for compliance

---

## PART 4: COMPARATIVE ANALYSIS

### 4.1 Comparison with TransferChain

| Feature | TransferChain | RoayatAlMostaqbal | Winner |
|---------|---------------|-------------------|--------|
| **Encryption Scope** | All data | Selective | RoayatAlMostaqbal (flexible) |
| **Key Management** | User-only | Hybrid | RoayatAlMostaqbal (resilient) |
| **Disaster Recovery** | No | Yes | RoayatAlMostaqbal |
| **Admin Support** | No | Yes | RoayatAlMostaqbal |
| **2FA Integration** | No | Yes | RoayatAlMostaqbal |
| **Audit Logging** | No | Yes | RoayatAlMostaqbal |
| **Privacy Level** | Maximum | High | TransferChain |
| **Usability** | Complex | Simple | RoayatAlMostaqbal |
| **Compliance** | Privacy-only | Audit-friendly | RoayatAlMostaqbal |
| **Use Case Fit** | File transfer | Services | Both (different domains) |

### 4.2 Comparison with Standard OAuth2

| Feature | Standard OAuth2 | RoayatAlMostaqbal | Difference |
|---------|-----------------|-------------------|-----------|
| **Authentication** | Yes | Yes | Same |
| **Encryption** | Server-side only | Hybrid CSE+SSE | RoayatAlMostaqbal (stronger) |
| **2FA** | Optional | Integrated | RoayatAlMostaqbal (built-in) |
| **Key Management** | Server-only | Hybrid | RoayatAlMostaqbal (distributed) |
| **User Data Protection** | Server-side | Client-side | RoayatAlMostaqbal (stronger) |
| **Recovery Codes** | Not standard | Encrypted | RoayatAlMostaqbal (unique) |
| **Audit Logging** | Basic | Comprehensive | RoayatAlMostaqbal (detailed) |

### 4.3 Comparison with Zero-Knowledge Services

| Feature | Zero-Knowledge Services | RoayatAlMostaqbal | Difference |
|---------|------------------------|-------------------|-----------|
| **Privacy** | Maximum | High | ZK Services (stricter) |
| **Admin Access** | No | Yes | RoayatAlMostaqbal (practical) |
| **Disaster Recovery** | No | Yes | RoayatAlMostaqbal |
| **Usability** | Complex | Simple | RoayatAlMostaqbal |
| **Compliance** | Privacy-only | Audit-friendly | RoayatAlMostaqbal |
| **2FA** | Not standard | Integrated | RoayatAlMostaqbal |
| **Use Case** | Privacy-focused | Services | Different domains |

---

## PART 5: SECURITY ANALYSIS

### 5.1 Threat Model

**Threats Addressed:**

1. **Data Breach at Rest**
   - Mitigation: Encryption at rest (CSE + SSE)
   - Status: ✅ Protected

2. **Data Breach in Transit**
   - Mitigation: HTTPS + TLS
   - Status: ✅ Protected

3. **Unauthorized Access**
   - Mitigation: Authentication + Authorization
   - Status: ✅ Protected

4. **Credential Theft**
   - Mitigation: Password hashing + 2FA
   - Status: ✅ Protected

5. **Key Compromise**
   - Mitigation: Hybrid key management
   - Status: ✅ Partially Protected

6. **Admin Abuse**
   - Mitigation: Audit logging + RBAC
   - Status: ✅ Monitored

### 5.2 Security Strengths

✅ Multi-layer encryption (CSE + SSE)
✅ Integrated 2FA system
✅ Hybrid key management
✅ Comprehensive audit logging
✅ Role-based access control
✅ Password hashing (bcrypt)
✅ HTTPS/TLS encryption
✅ Nonce and salt usage

### 5.3 Security Considerations

⚠️ Server has MEK (not pure zero-knowledge)
⚠️ Admin can access encrypted data (with audit)
⚠️ User responsible for password security
⚠️ 2FA device security depends on user

---

## PART 6: COMPLIANCE MAPPING

### 6.1 GDPR Compliance

| Requirement | Implementation | Status |
|------------|-----------------|--------|
| Data Encryption | CSE + SSE | ✅ |
| User Control | User manages DEK | ✅ |
| Data Deletion | Supported | ✅ |
| Audit Trail | Comprehensive logging | ✅ |
| Consent | Explicit 2FA setup | ✅ |

### 6.2 HIPAA Compliance

| Requirement | Implementation | Status |
|------------|-----------------|--------|
| Encryption | AES-256 | ✅ |
| Access Control | RBAC + 2FA | ✅ |
| Audit Logging | Comprehensive | ✅ |
| Data Integrity | HMAC verification | ✅ |
| Disaster Recovery | MEK backup | ✅ |

---

**Document Status:** Complete
**Version:** 1.0
**Date:** November 2025
**Ready for:** Patent Examination
