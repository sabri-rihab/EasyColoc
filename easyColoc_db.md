# EasyColoc - Database Documentation

## Table of Contents
1. [Database Tables](#database-tables)
2. [Entity Relationship Diagram (ERD)](#entity-relationship-diagram-erd)
3. [Class Diagram](#class-diagram)
4. [MCD (Modèle Conceptuel de Données)](#mcd-modèle-conceptuel-de-données)

---

## Database Tables

### 1. users
**Description:** Stores user accounts and authentication data

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| name | VARCHAR(255) | NOT NULL | User's full name |
| email | VARCHAR(255) | UNIQUE, NOT NULL | User's email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| reputation | INTEGER | DEFAULT 0 | User reputation score |
| is_global_admin | BOOLEAN | DEFAULT false | Admin flag |
| is_banned | BOOLEAN | DEFAULT false | Ban status |
| banned_at | TIMESTAMP | NULLABLE | Ban timestamp |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

### 2. colocations
**Description:** Stores colocation (shared living) groups

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique colocation identifier |
| name | VARCHAR(255) | NOT NULL | Colocation name |
| adresse | TEXT | NOT NULL | Address |
| owner_id | BIGINT UNSIGNED | FOREIGN KEY → users(id), CASCADE | Owner user ID |
| invitation_code | VARCHAR(255) | UNIQUE, NOT NULL | Invitation code for joining |
| is_active | BOOLEAN | DEFAULT true | Active status |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes:**
- `owner_id` (Foreign Key)
- `invitation_code` (Unique)

---

### 3. colocations_user (Pivot Table)
**Description:** Many-to-many relationship between users and colocations

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique record identifier |
| colocation_id | BIGINT UNSIGNED | FOREIGN KEY → colocations(id), CASCADE | Colocation reference |
| user_id | BIGINT UNSIGNED | FOREIGN KEY → users(id), CASCADE | User reference |
| is_owner | BOOLEAN | DEFAULT false | Ownership flag |
| joined_at | DATE | NULLABLE | Join date |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes:**
- `colocation_id` (Foreign Key)
- `user_id` (Foreign Key)
- UNIQUE(`colocation_id`, `user_id`)

---

### 4. expenses
**Description:** Stores shared expenses within colocations

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique expense identifier |
| colocation_id | BIGINT UNSIGNED | FOREIGN KEY → colocations(id), CASCADE | Colocation reference |
| payer_id | BIGINT UNSIGNED | FOREIGN KEY → users(id), CASCADE | User who paid |
| title | VARCHAR(255) | NOT NULL | Expense title |
| description | TEXT | NULLABLE | Expense description |
| amount | DECIMAL(10,2) | NOT NULL | Total amount |
| category | VARCHAR(255) | NULLABLE | Expense category |
| expense_date | DATE | NOT NULL | Date of expense |
| is_settled | BOOLEAN | DEFAULT false | Settlement status |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes:**
- `colocation_id` (Foreign Key)
- `payer_id` (Foreign Key)

---

### 5. expense_user (Pivot Table)
**Description:** Tracks individual debts for each expense

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique record identifier |
| expense_id | BIGINT UNSIGNED | FOREIGN KEY → expenses(id), CASCADE | Expense reference |
| user_id | BIGINT UNSIGNED | FOREIGN KEY → users(id), CASCADE | User who owes |
| amount_owed | DECIMAL(10,2) | NOT NULL | Amount this user owes |
| is_paid | BOOLEAN | DEFAULT false | Payment status |
| paid_at | TIMESTAMP | NULLABLE | Payment timestamp |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes:**
- `expense_id` (Foreign Key)
- `user_id` (Foreign Key)
- UNIQUE(`expense_id`, `user_id`)

---

### 6. invitations
**Description:** Stores colocation invitations sent to users

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique invitation identifier |
| colocation_id | BIGINT UNSIGNED | FOREIGN KEY → colocations(id), CASCADE | Colocation reference |
| inviter_id | BIGINT UNSIGNED | FOREIGN KEY → users(id), CASCADE | User who sent invitation |
| email | VARCHAR(255) | NOT NULL | Invitee email address |
| token | VARCHAR(255) | UNIQUE, NOT NULL | Invitation token |
| accepted_at | TIMESTAMP | NULLABLE | Acceptance timestamp |
| expires_at | TIMESTAMP | NULLABLE | Expiration timestamp |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes:**
- `colocation_id` (Foreign Key)
- `inviter_id` (Foreign Key)
- `token` (Unique)

---

### 7. categories
**Description:** Predefined expense categories

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique category identifier |
| name | VARCHAR(255) | NOT NULL | Category name |
| slug | VARCHAR(255) | UNIQUE, NOT NULL | URL-friendly slug |
| icon | VARCHAR(255) | NULLABLE | Emoji icon |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Default Categories:**
- Alimentation 🛒
- Loyer / Charges 🏠
- Électricité ⚡
- Eau 💧
- Internet 📡
- Transport 🚗
- Autre 💰

---

### 8. password_reset_tokens
**Description:** Laravel's password reset tokens

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| email | VARCHAR(255) | PRIMARY KEY | User email |
| token | VARCHAR(255) | NOT NULL | Reset token |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |

---

### 9. sessions
**Description:** Laravel's session storage

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | VARCHAR(255) | PRIMARY KEY | Session ID |
| user_id | BIGINT UNSIGNED | NULLABLE, INDEXED | User reference |
| ip_address | VARCHAR(45) | NULLABLE | Client IP |
| user_agent | TEXT | NULLABLE | Browser info |
| payload | LONGTEXT | NOT NULL | Session data |
| last_activity | INTEGER | INDEXED | Last activity timestamp |

---

### 10. cache & cache_locks
**Description:** Laravel's cache system (created by migration but structure varies)

---

## Entity Relationship Diagram (ERD)

```
┌─────────────────┐
│     users       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (UNIQUE)  │
│ password        │
│ reputation      │
│ is_global_admin │
│ is_banned       │
│ banned_at       │
└────────┬────────┘
         │
         │ 1:N (owner)
         │
         ▼
┌─────────────────────┐
│   colocations       │
├─────────────────────┤
│ id (PK)             │
│ name                │
│ adresse             │
│ owner_id (FK)       │◄────┐
│ invitation_code     │     │
│ is_active           │     │
└──────────┬──────────┘     │
           │                │
           │ N:M            │ 1:N
           │                │
           ▼                │
┌──────────────────────┐    │
│  colocations_user    │    │
│  (Pivot Table)       │    │
├──────────────────────┤    │
│ id (PK)              │    │
│ colocation_id (FK)   │────┘
│ user_id (FK)         │────┐
│ is_owner             │    │
│ joined_at            │    │
└──────────────────────┘    │
                            │
                            │
           ┌────────────────┘
           │
           │ N:M
           │
           ▼
┌─────────────────┐
│    expenses     │
├─────────────────┤
│ id (PK)         │
│ colocation_id   │◄────┐
│ payer_id (FK)   │     │
│ title           │     │ 1:N
│ amount          │     │
│ category        │     │
│ expense_date    │     │
│ is_settled      │     │
└────────┬────────┘     │
         │              │
         │ N:M          │
         │              │
         ▼              │
┌──────────────────┐    │
│  expense_user    │    │
│  (Pivot Table)   │    │
├──────────────────┤    │
│ id (PK)          │    │
│ expense_id (FK)  │────┘
│ user_id (FK)     │────┐
│ amount_owed      │    │
│ is_paid          │    │
│ paid_at          │    │
└──────────────────┘    │
                        │
           ┌────────────┘
           │
           │ N:1
           │
           ▼
┌─────────────────┐
│  invitations    │
├─────────────────┤
│ id (PK)         │
│ colocation_id   │
│ inviter_id (FK) │
│ email           │
│ token (UNIQUE)  │
│ accepted_at     │
│ expires_at      │
└─────────────────┘

┌─────────────────┐
│   categories    │
├─────────────────┤
│ id (PK)         │
│ name            │
│ slug (UNIQUE)   │
│ icon            │
└─────────────────┘
```

---

## Class Diagram

```
┌──────────────────────────────────────┐
│            User                      │
├──────────────────────────────────────┤
│ - id: int                            │
│ - name: string                       │
│ - email: string                      │
│ - password: string                   │
│ - reputation: int                    │
│ - is_global_admin: bool              │
│ - is_banned: bool                    │
│ - banned_at: timestamp               │
├──────────────────────────────────────┤
│ + colocations(): BelongsToMany       │
│ + ownedColocation(): HasOne          │
│ + expensesOwed(): BelongsToMany      │
│ + expensesPaid(): HasMany            │
│ + hasActiveColocation(): bool        │
│ + currentColocation(): Colocation    │
│ + getColocationBalance(): float      │
└──────────────┬───────────────────────┘
               │
               │ N:M (members)
               │
               ▼
┌──────────────────────────────────────┐
│          Colocation                  │
├──────────────────────────────────────┤
│ - id: int                            │
│ - name: string                       │
│ - adresse: string                    │
│ - owner_id: int                      │
│ - invitation_code: string            │
│ - is_active: bool                    │
├──────────────────────────────────────┤
│ + owner(): BelongsTo                 │
│ + members(): BelongsToMany           │
│ + expenses(): HasMany                │
│ + invitations(): HasMany             │
│ + hasMember(User): bool              │
│ + isOwner(User): bool                │
│ + boot(): void (generates code)      │
└──────────────┬───────────────────────┘
               │
               │ 1:N
               │
               ▼
┌──────────────────────────────────────┐
│           Expense                    │
├──────────────────────────────────────┤
│ - id: int                            │
│ - colocation_id: int                 │
│ - payer_id: int                      │
│ - title: string                      │
│ - description: string                │
│ - amount: decimal                    │
│ - category: string                   │
│ - expense_date: date                 │
│ - is_settled: bool                   │
├──────────────────────────────────────┤
│ + colocation(): BelongsTo            │
│ + payer(): BelongsTo                 │
│ + debtors(): BelongsToMany           │
└──────────────────────────────────────┘

┌──────────────────────────────────────┐
│         Invitation                   │
├──────────────────────────────────────┤
│ - id: int                            │
│ - colocation_id: int                 │
│ - inviter_id: int                    │
│ - email: string                      │
│ - token: string                      │
│ - accepted_at: timestamp             │
│ - expires_at: timestamp              │
├──────────────────────────────────────┤
│ + colocation(): BelongsTo            │
│ + inviter(): BelongsTo               │
└──────────────────────────────────────┘

┌──────────────────────────────────────┐
│          Category                    │
├──────────────────────────────────────┤
│ - id: int                            │
│ - name: string                       │
│ - slug: string                       │
│ - icon: string                       │
└──────────────────────────────────────┘

┌──────────────────────────────────────┐
│    <<Trait>> DebtTransferable        │
├──────────────────────────────────────┤
│ + transferDebt(Colocation, User, int)│
│ + calculateDebt(Colocation, User)    │
└──────────────────────────────────────┘
```

---

## MCD (Modèle Conceptuel de Données)

```
┌─────────────┐
│   UTILISATEUR│
├─────────────┤
│ id          │
│ nom         │
│ email       │
│ mot_de_passe│
│ réputation  │
│ est_admin   │
│ est_banni   │
└──────┬──────┘
       │
       │ possède (1,1)
       │
       ▼
┌──────────────┐         appartient (0,N)         ┌─────────────┐
│  COLOCATION  │◄────────────────────────────────►│ UTILISATEUR │
├──────────────┤                                   └─────────────┘
│ id           │         APPARTENANCE
│ nom          │         ┌──────────────┐
│ adresse      │         │ est_proprio  │
│ code_invit   │         │ date_arrivée │
│ est_active   │         └──────────────┘
└──────┬───────┘
       │
       │ contient (0,N)
       │
       ▼
┌──────────────┐
│   DÉPENSE    │
├──────────────┤
│ id           │
│ titre        │
│ montant      │
│ catégorie    │
│ date_dépense │
│ est_réglée   │
└──────┬───────┘
       │
       │ concerne (1,N)
       │
       ▼
┌──────────────┐         DETTE
│ UTILISATEUR  │         ┌──────────────┐
└──────────────┘         │ montant_dû   │
                         │ est_payé     │
                         │ date_paiement│
                         └──────────────┘

┌──────────────┐
│  INVITATION  │
├──────────────┤
│ id           │
│ email        │
│ token        │
│ date_accept  │
│ date_expir   │
└──────────────┘
       │
       │ envoyée_par (1,1)
       │
       ▼
┌──────────────┐
│ UTILISATEUR  │
└──────────────┘
       │
       │ pour (1,1)
       │
       ▼
┌──────────────┐
│  COLOCATION  │
└──────────────┘

┌──────────────┐
│  CATÉGORIE   │
├──────────────┤
│ id           │
│ nom          │
│ slug         │
│ icône        │
└──────────────┘
```

### Cardinalités Détaillées

**UTILISATEUR - COLOCATION (Appartenance)**
- Un utilisateur peut appartenir à 0 ou plusieurs colocations (0,N)
- Une colocation contient 1 ou plusieurs utilisateurs (1,N)
- Attributs: est_proprio, date_arrivée

**UTILISATEUR - COLOCATION (Propriété)**
- Un utilisateur peut posséder 0 ou 1 colocation (0,1)
- Une colocation a exactement 1 propriétaire (1,1)

**COLOCATION - DÉPENSE**
- Une colocation contient 0 ou plusieurs dépenses (0,N)
- Une dépense appartient à exactement 1 colocation (1,1)

**UTILISATEUR - DÉPENSE (Paiement)**
- Un utilisateur peut payer 0 ou plusieurs dépenses (0,N)
- Une dépense est payée par exactement 1 utilisateur (1,1)

**UTILISATEUR - DÉPENSE (Dette)**
- Un utilisateur peut devoir 0 ou plusieurs dépenses (0,N)
- Une dépense concerne 1 ou plusieurs utilisateurs (1,N)
- Attributs: montant_dû, est_payé, date_paiement

**UTILISATEUR - INVITATION (Envoi)**
- Un utilisateur peut envoyer 0 ou plusieurs invitations (0,N)
- Une invitation est envoyée par exactement 1 utilisateur (1,1)

**COLOCATION - INVITATION**
- Une colocation peut avoir 0 ou plusieurs invitations (0,N)
- Une invitation concerne exactement 1 colocation (1,1)

---

## Règles de Gestion

1. **Création de Colocation**
   - Un utilisateur crée une colocation et devient automatiquement propriétaire
   - Un code d'invitation unique est généré automatiquement
   - Le créateur est ajouté comme premier membre

2. **Gestion des Membres**
   - Seul le propriétaire peut retirer des membres
   - Un membre peut quitter volontairement
   - Si le propriétaire quitte, le membre le plus ancien devient propriétaire
   - Si tous les membres quittent, la colocation devient inactive

3. **Gestion des Dépenses**
   - Une dépense est divisée équitablement entre tous les membres
   - Le payeur ne se doit pas d'argent à lui-même
   - Les dettes sont enregistrées dans expense_user

4. **Transfert de Dettes**
   - Quand un membre quitte, ses dettes sont transférées au propriétaire
   - Quand le propriétaire est banni, ses dettes vont au nouveau propriétaire
   - La réputation est ajustée selon le montant des dettes

5. **Bannissement**
   - Un admin peut bannir un utilisateur
   - L'utilisateur est retiré de toutes ses colocations
   - Ses dettes sont transférées
   - Sa réputation est ajustée

6. **Réputation**
   - Augmente quand on quitte sans dettes
   - Diminue quand on quitte avec des dettes
   - Influence la confiance dans le système
