# WebSecure App

## Database

You need to create an sqlite database inside the `storage` folder

Then execute the following sql statements:

```sql

-- Users Table: store users
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Verification codes table: stores 2FA verification codes
CREATE TABLE verification_codes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    code VARCHAR(6) NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    expiration DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Roles Table: Predefined roles (e.g., admin, user)
CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    description TEXT
);

-- User Roles Table: Assign roles to users
CREATE TABLE user_roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    role_id INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE (user_id, role_id)
);

-- Temporary Roles Table: Track JIT (Just-in-Time) roles
CREATE TABLE temporary_roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    role_id INTEGER NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE (user_id, role_id)
);

-- Insert predefined roles
INSERT INTO roles (name, description) VALUES 
('admin', 'Administrator with full access'),
('user', 'Regular user with basic access'),
('resource_access', 'Temporary access to specific resources');

```

## Storage

```cmd
mkdir public\storage
mklink /D public\storage\resources storage\resources
```