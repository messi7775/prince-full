CREATE DATABASE IF NOT EXISTS prince_cards
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE prince_cards;

-- ---------------------------------------------------------------------------
-- Admin (single authenticated account)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO admins (email, password_hash)
VALUES ('ibrabra651@gmail.com', '$2y$12$OksurUunRjk.srg0GLmqPOND.WknqH71YwE4m4vYoPkLo46o8/gci')
ON DUPLICATE KEY UPDATE
    password_hash = VALUES(password_hash),
    updated_at = CURRENT_TIMESTAMP;

-- ---------------------------------------------------------------------------
-- Packages (card denominations)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS packages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    size VARCHAR(64) NULL,
    hours INT UNSIGNED NULL,
    color VARCHAR(32) NULL,
    duration VARCHAR(64) NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------------
-- Inventory (stock batches — unit_price captured for historical accuracy)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS inventory (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id INT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('active','closed') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_inventory_package FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------------------------
-- Distributors (resellers)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS distributors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    phone VARCHAR(32) NULL,
    balance DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------------
-- Sales (unit_price stored per item for historical pricing)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sales (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    distributor_id INT UNSIGNED NULL,
    package_id INT UNSIGNED NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_type ENUM('cash','credit') NOT NULL DEFAULT 'cash',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sale_distributor FOREIGN KEY (distributor_id) REFERENCES distributors(id) ON DELETE SET NULL,
    CONSTRAINT fk_sale_package FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------------------------
-- Payments (collections from distributors — full or partial)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    distributor_id INT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    note VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payment_distributor FOREIGN KEY (distributor_id) REFERENCES distributors(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------------------------
-- Lines (telecom line accounts)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lines` (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    provider VARCHAR(190) NULL,
    balance DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------------
-- Expenses (operating costs)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS expenses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(190) NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    note VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------------
-- Cash movements (every cash in/out event)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cash_movements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    direction ENUM('in','out') NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    reason VARCHAR(190) NOT NULL,
    reference_type VARCHAR(64) NULL,
    reference_id INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------------
-- Audit log
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNSIGNED NULL,
    action VARCHAR(190) NOT NULL,
    description VARCHAR(255) NULL,
    context TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);
