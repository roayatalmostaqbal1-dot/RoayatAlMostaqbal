# Architecture Diagrams
## RoayatAlMostaqbal - Detailed System Architecture with Mermaid Diagrams

**Document Purpose:** Visual representation of system architecture, data flows, and component relationships using Mermaid diagrams.

**Date:** November 2025
**Status:** Patent Examination Support Document

---

## DIAGRAM 1: System Component Architecture

```mermaid
graph TB
    subgraph Client["Client Layer"]
        VueApp["Vue.js Application"]
        BladeViews["Blade Templates"]
        LocalStorage["Local Storage<br/>(DEK, Nonce)"]
    end
    
    subgraph API["API Layer"]
        AuthController["Authentication<br/>Controller"]
        TwoFAController["2FA<br/>Controller"]
        EncryptedDataController["Encrypted Data<br/>Controller"]
        Middleware["Authorization<br/>Middleware"]
    end
    
    subgraph Encryption["Encryption Layer"]
        CSEHandler["CSE Handler<br/>(Client-Side)"]
        CSKEHandler["CSKE Handler<br/>(Credentials)"]
        KeyManager["Key Manager<br/>(DEK/MEK)"]
    end
    
    subgraph Database["Database Layer"]
        UsersTable["Users Table"]
        TwoFactorTable["2FA Table"]
        EncryptedDataTable["Encrypted Data<br/>Table"]
        AuditTable["Audit Log Table"]
    end
    
    VueApp -->|HTTPS| AuthController
    BladeViews -->|HTTPS| AuthController
    AuthController -->|Verify| Middleware
    Middleware -->|Check Permissions| TwoFAController
    TwoFAController -->|Encrypt/Decrypt| CSKEHandler
    EncryptedDataController -->|Handle| CSEHandler
    CSEHandler -->|Manage Keys| KeyManager
    CSKEHandler -->|Manage Keys| KeyManager
    AuthController -->|Store| UsersTable
    TwoFAController -->|Store| TwoFactorTable
    EncryptedDataController -->|Store| EncryptedDataTable
    Middleware -->|Log| AuditTable
    
    style Client fill:#051824,stroke:#27e9b5,color:#fff
    style API fill:#162936,stroke:#27e9b5,color:#fff
    style Encryption fill:#051824,stroke:#27e9b5,color:#fff
    style Database fill:#162936,stroke:#27e9b5,color:#fff
```

---

## DIAGRAM 2: Authentication Flow

```mermaid
sequenceDiagram
    participant User
    participant Client as Client App
    participant Server as Laravel Server
    participant DB as Database
    
    User->>Client: Enter Email & Password
    Client->>Client: Validate Input
    Client->>Client: Hash Password
    Client->>Server: POST /api/v1/auth/login
    
    Server->>DB: Query User by Email
    DB-->>Server: User Record
    Server->>Server: Verify Password Hash
    
    alt 2FA Enabled
        Server-->>Client: 2FA Required
        Client->>User: Request TOTP Code
        User->>Client: Enter 6-digit Code
        Client->>Server: POST /api/v1/auth/2fa/verify
        Server->>DB: Get Encrypted Secret
        DB-->>Server: Encrypted Secret
        Server->>Server: Decrypt Secret
        Server->>Server: Verify TOTP Code
        Server-->>Client: Token + Success
    else 2FA Disabled
        Server->>Server: Generate Token
        Server-->>Client: Token + Success
    end
    
    Client->>Client: Store Token
    Client->>User: Login Success
```

---

## DIAGRAM 3: Data Encryption Flow

```mermaid
graph LR
    subgraph Client["Client Side"]
        UserData["User Profile<br/>Data"]
        GenDEK["Generate DEK<br/>(Locally)"]
        DeriveKey["Derive Key<br/>(HKDF)"]
        Encrypt["Encrypt Data<br/>(AES-256)"]
        Payload["Create Payload<br/>(encrypted_dek,<br/>ciphertext)"]
    end
    
    subgraph Network["Network"]
        HTTPS["HTTPS<br/>Transmission"]
    end
    
    subgraph Server["Server Side"]
        Receive["Receive<br/>Encrypted Payload"]
        Validate["Validate<br/>Structure"]
        Store["Store in<br/>Database"]
        NoDecrypt["NO Decryption<br/>on Server"]
    end
    
    UserData -->|Input| GenDEK
    GenDEK -->|Generate| DeriveKey
    DeriveKey -->|Derive| Encrypt
    Encrypt -->|Output| Payload
    Payload -->|Send| HTTPS
    HTTPS -->|Receive| Receive
    Receive -->|Verify| Validate
    Validate -->|Store| Store
    Store -->|Maintain| NoDecrypt
    
    style Client fill:#051824,stroke:#27e9b5,color:#fff
    style Network fill:#162936,stroke:#27e9b5,color:#fff
    style Server fill:#051824,stroke:#27e9b5,color:#fff
```

---

## DIAGRAM 4: 2FA Verification Flow

```mermaid
graph TD
    A["User Enters TOTP Code"] -->|Submit| B["Server Receives Code"]
    B --> C{"Code Valid?"}
    
    C -->|YES| D["Verify Against<br/>Decrypted Secret"]
    D --> E{"Match?"}
    
    E -->|YES| F["Issue OAuth2 Token"]
    F --> G["Return Success"]
    
    E -->|NO| H["Check Recovery<br/>Codes"]
    H --> I{"Recovery Code<br/>Valid?"}
    
    I -->|YES| J["Remove Used Code<br/>from List"]
    J --> K["Encrypt Updated<br/>Codes"]
    K --> L["Store in DB"]
    L --> F
    
    I -->|NO| M["Log Failed<br/>Attempt"]
    M --> N["Return Error"]
    
    C -->|NO| N
    
    G --> O["User Authenticated"]
    N --> P["Authentication Failed"]
    
    style A fill:#27e9b5,stroke:#051824,color:#051824
    style O fill:#27e9b5,stroke:#051824,color:#051824
    style P fill:#ff6b6b,stroke:#051824,color:#fff
```

---

## DIAGRAM 5: Key Management Architecture

```mermaid
graph TB
    subgraph UserSide["User Side"]
        Password["User Password"]
        LocalDEK["Data Encryption<br/>Key DEK"]
        Salt["Salt<br/>(Random)"]
        Nonce["Nonce<br/>(Random)"]
    end
    
    subgraph Derivation["Key Derivation"]
        HKDF["HKDF<br/>Key Derivation"]
    end
    
    subgraph ServerSide["Server Side"]
        MEK["Master Encryption<br/>Key MEK"]
        EncryptedDEK["Encrypted DEK<br/>(with MEK)"]
        EncryptedSecrets["Encrypted 2FA<br/>Secrets"]
    end
    
    subgraph Database["Database Storage"]
        DBEncryptedDEK["encrypted_dek"]
        DBSalt["dek_salt"]
        DBNonce["dek_nonce"]
        DBSecrets["encrypted_secrets"]
    end
    
    Password -->|Input| HKDF
    Salt -->|Input| HKDF
    HKDF -->|Output| LocalDEK
    LocalDEK -->|Encrypt| EncryptedDEK
    MEK -->|Encrypt| EncryptedDEK
    EncryptedDEK -->|Store| DBEncryptedDEK
    Salt -->|Store| DBSalt
    Nonce -->|Store| DBNonce
    MEK -->|Encrypt| EncryptedSecrets
    EncryptedSecrets -->|Store| DBSecrets
    
    style UserSide fill:#051824,stroke:#27e9b5,color:#fff
    style Derivation fill:#162936,stroke:#27e9b5,color:#fff
    style ServerSide fill:#051824,stroke:#27e9b5,color:#fff
    style Database fill:#162936,stroke:#27e9b5,color:#fff
```

---

## DIAGRAM 6: Role-Based Access Control

```mermaid
graph TD
    User["User Request"]
    
    User -->|Authenticate| Auth["Authentication<br/>Check"]
    Auth -->|Verify Token| TokenValid{"Token<br/>Valid?"}
    
    TokenValid -->|NO| Reject1["Reject Request"]
    TokenValid -->|YES| GetRole["Get User Role"]
    
    GetRole --> RoleCheck{"User Role<br/>Authorized?"}
    RoleCheck -->|NO| Reject2["Reject Request"]
    RoleCheck -->|YES| PermCheck["Check Specific<br/>Permissions"]
    
    PermCheck --> PermValid{"Permission<br/>Granted?"}
    PermValid -->|NO| Reject3["Reject Request"]
    PermValid -->|YES| Execute["Execute Request"]
    
    Execute --> LogAccess["Log Access<br/>in Audit Trail"]
    LogAccess --> Return["Return Response"]
    
    Reject1 --> Error["Return 401<br/>Unauthorized"]
    Reject2 --> Error
    Reject3 --> Error403["Return 403<br/>Forbidden"]
    
    style User fill:#27e9b5,stroke:#051824,color:#051824
    style Return fill:#27e9b5,stroke:#051824,color:#051824
    style Error fill:#ff6b6b,stroke:#051824,color:#fff
    style Error403 fill:#ff6b6b,stroke:#051824,color:#fff
```

---

## DIAGRAM 7: Database Schema Relationships

```mermaid
erDiagram
    USERS ||--o{ USER_TWO_FACTOR_AUTH : has
    USERS ||--o{ ENCRYPTED_USER_DATA : has
    USERS ||--o{ AUDIT_LOGS : generates
    
    USERS {
        int id PK
        string email UK
        string name
        string password_hash
        timestamp created_at
        timestamp updated_at
    }
    
    USER_TWO_FACTOR_AUTH {
        int id PK
        int user_id FK
        boolean is_enabled
        string encrypted_secret
        string encrypted_recovery_codes
        timestamp created_at
        timestamp updated_at
    }
    
    ENCRYPTED_USER_DATA {
        int id PK
        int user_id FK
        string encrypted_dek
        string dek_salt
        string dek_nonce
        string profile_ciphertext
        string profile_nonce
        string data_type
        string metadata
        timestamp created_at
        timestamp updated_at
    }
    
    AUDIT_LOGS {
        int id PK
        int user_id FK
        string action
        string resource
        string ip_address
        string user_agent
        timestamp created_at
    }
```

---

## DIAGRAM 8: Comparative Architecture - TransferChain vs RoayatAlMostaqbal

```mermaid
graph TB
    subgraph TC["TransferChain Architecture"]
        TCClient["Client Device"]
        TCCSE["Pure CSE<br/>(All Data)"]
        TCServer["Server<br/>(Storage Only)"]
        TCDB["Database<br/>(Encrypted Only)"]
    end
    
    subgraph RAM["RoayatAlMostaqbal Architecture"]
        RAMClient["Client Device"]
        RAMCSE["CSE<br/>(User Data)"]
        RAMSSE["SSE<br/>(Credentials)"]
        RAM2FA["2FA System<br/>(TOTP)"]
        RAMRBAC["RBAC<br/>(Authorization)"]
        RAMServer["Server<br/>(Storage +<br/>Auth +<br/>Encryption)"]
        RAMDB["Database<br/>(Encrypted +<br/>Audit Logs)"]
    end
    
    TCClient -->|Encrypt| TCCSE
    TCCSE -->|Send| TCServer
    TCServer -->|Store| TCDB
    
    RAMClient -->|Encrypt| RAMCSE
    RAMClient -->|Generate| RAM2FA
    RAMCSE -->|Send| RAMServer
    RAM2FA -->|Send| RAMServer
    RAMServer -->|Encrypt| RAMSSE
    RAMServer -->|Verify| RAMRBAC
    RAMServer -->|Store| RAMDB
    
    style TC fill:#162936,stroke:#27e9b5,color:#fff
    style RAM fill:#051824,stroke:#27e9b5,color:#fff
```

---

## DIAGRAM 9: Security Layers

```mermaid
graph TB
    subgraph L1["Layer 1: Transport Security"]
        HTTPS["HTTPS/TLS<br/>Encryption"]
    end
    
    subgraph L2["Layer 2: Authentication"]
        Password["Password<br/>Hashing"]
        TwoFA["2FA<br/>TOTP"]
    end
    
    subgraph L3["Layer 3: Data Encryption"]
        CSE["Client-Side<br/>Encryption"]
        SSE["Server-Side<br/>Encryption"]
    end
    
    subgraph L4["Layer 4: Authorization"]
        RBAC["Role-Based<br/>Access Control"]
        Permissions["Permission<br/>Verification"]
    end
    
    subgraph L5["Layer 5: Audit"]
        Logging["Comprehensive<br/>Audit Logging"]
        Monitoring["Access<br/>Monitoring"]
    end
    
    User["User Request"] -->|Secure| HTTPS
    HTTPS -->|Authenticate| Password
    Password -->|Verify| TwoFA
    TwoFA -->|Encrypt| CSE
    CSE -->|Encrypt| SSE
    SSE -->|Authorize| RBAC
    RBAC -->|Verify| Permissions
    Permissions -->|Log| Logging
    Logging -->|Monitor| Monitoring
    Monitoring -->|Response| User
    
    style L1 fill:#051824,stroke:#27e9b5,color:#fff
    style L2 fill:#162936,stroke:#27e9b5,color:#fff
    style L3 fill:#051824,stroke:#27e9b5,color:#fff
    style L4 fill:#162936,stroke:#27e9b5,color:#fff
    style L5 fill:#051824,stroke:#27e9b5,color:#fff
```

---

## DIAGRAM 10: Innovation Comparison Matrix

```mermaid
graph LR
    subgraph Features["Key Features"]
        F1["Selective<br/>Encryption"]
        F2["Hybrid Key<br/>Management"]
        F3["Integrated<br/>2FA"]
        F4["Admin Access<br/>with Audit"]
        F5["Bilingual<br/>Interface"]
    end
    
    subgraph TC["TransferChain"]
        TC1["❌"]
        TC2["❌"]
        TC3["❌"]
        TC4["❌"]
        TC5["❌"]
    end
    
    subgraph RAM["RoayatAlMostaqbal"]
        RAM1["✅"]
        RAM2["✅"]
        RAM3["✅"]
        RAM4["✅"]
        RAM5["✅"]
    end
    
    F1 --> TC1
    F1 --> RAM1
    F2 --> TC2
    F2 --> RAM2
    F3 --> TC3
    F3 --> RAM3
    F4 --> TC4
    F4 --> RAM4
    F5 --> TC5
    F5 --> RAM5
    
    style Features fill:#27e9b5,stroke:#051824,color:#051824
    style TC fill:#ff6b6b,stroke:#051824,color:#fff
    style RAM fill:#27e9b5,stroke:#051824,color:#051824
```

---

**Document Status:** Complete
**Version:** 1.0
**Date:** November 2025
**Ready for:** Patent Examination

