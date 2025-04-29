-- User Profile
CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id SERIAL PRIMARY KEY,    -- Primary Key for user_profiles
    profile_name VARCHAR(50) NOT NULL,         -- Profile name (e.g., Homeowner, Cleaner)
    is_suspended BOOLEAN NOT NULL      -- Profile suspension status
);

-- User Account
CREATE TABLE IF NOT EXISTS user_accounts (
    account_id SERIAL PRIMARY KEY,        -- Primary Key for user_accounts
    ua_username VARCHAR(50) UNIQUE NOT NULL, -- Username (unique)
    ua_password VARCHAR(255) NOT NULL,       -- Password
    profile_name VARCHAR(20) NOT NULL,         -- Profile name (Admin, Homeowner, Cleaner, etc.)
    profile_id INT NOT NULL,              -- Foreign key to user_profiles table
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE,  -- Account suspension status
    FOREIGN KEY (profile_id) REFERENCES user_profiles(profile_id) ON DELETE CASCADE -- Link to user_profiles
);
    
-- Insert profiles into user_profiles
INSERT INTO user_profiles (profile_name, is_suspended)
VALUES
('User Admin', FALSE),
('Homeowner', FALSE),
('Cleaner', FALSE),
('Platform Management', FALSE);

-- Insert users into user_accounts with profile_ids
INSERT INTO user_accounts (ua_username, ua_password, profile_name, profile_id, is_suspended)
VALUES
('admin', '1234', 'User Admin', 1, FALSE),
('homeowner', '1234', 'Homeowner', 2, FALSE),
('cleaner', '1234', 'Cleaner', 3, FALSE),	
('platformmgmt', '1234', 'Platform Management', 4, FALSE);

/*
ALTER TABLE user_profiles
RENAME COLUMN name TO profile_name;

*/

select * from user_accounts;
-- drop table user_accounts;
select * from user_profiles;
-- drop table user_profiles;
SELECT column_name
FROM information_schema.columns
WHERE table_name = 'user_accounts';


-- Cleaner

CREATE TABLE IF NOT EXISTS services (
    service_id SERIAL PRIMARY KEY,
    cleaner_id INT NOT NULL,  -- Foreign key to the user_accounts table
    service_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2),
    availability BOOLEAN DEFAULT TRUE,
    views_count INT DEFAULT 0,
    shortlisted_count INT DEFAULT 0,
    status VARCHAR(20) DEFAULT 'active',  -- 'active' or 'suspended'
    FOREIGN KEY (cleaner_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS shortlist (
    shortlist_id SERIAL PRIMARY KEY,
    home_owner_id INT NOT NULL,  -- References home owners
    service_id INT NOT NULL,     -- References services
    FOREIGN KEY (home_owner_id) REFERENCES user_accounts(account_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id)
);

CREATE TABLE IF NOT EXISTS bookings (
    booking_id SERIAL PRIMARY KEY,
    home_owner_id INT NOT NULL,   -- References home owners
    service_id INT NOT NULL,      -- References services
    booking_date DATE NOT NULL,   -- Date of service booking
    status VARCHAR(20) DEFAULT 'confirmed',  -- Status (confirmed, completed, etc.)
    FOREIGN KEY (home_owner_id) REFERENCES user_accounts(account_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id)
);

CREATE TABLE IF NOT EXISTS service_categories (
    category_id SERIAL PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,  -- Category name (e.g., "Home Cleaning")
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE
);

-- Insert service categories into service_categories table
INSERT INTO service_categories (category_name, is_suspended)
VALUES
('Home Cleaning', FALSE),
('Deep Cleaning', FALSE),
('Carpet Cleaning', FALSE),
('Window Cleaning', FALSE),
('Office Cleaning', FALSE),
('Move-in/Move-out Cleaning', FALSE),
('Post-Construction Cleaning', FALSE),
('Laundry Services', FALSE),
('Upholstery Cleaning', FALSE),
('Pressure Washing', FALSE);


CREATE TABLE IF NOT EXISTS match (
    match_id SERIAL PRIMARY KEY,
    home_owner_id INT NOT NULL,
    cleaner_id INT NOT NULL,
    service_id INT NOT NULL,  -- Foreign key to services
    price DECIMAL(10, 2),
    status VARCHAR(20) DEFAULT 'pending',  -- Match status
    FOREIGN KEY (home_owner_id) REFERENCES user_accounts(account_id),
    FOREIGN KEY (cleaner_id) REFERENCES user_accounts(account_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id)
);


CREATE TABLE IF NOT EXISTS match_request (
    match_request_id SERIAL PRIMARY KEY,
    home_owner_id INT NOT NULL,   -- References home owner
    service_id INT NOT NULL,      -- References the service being requested
    cleaner_id INT NOT NULL,      -- Cleaner being requested
    status VARCHAR(20) DEFAULT 'pending',  -- Request status
    FOREIGN KEY (home_owner_id) REFERENCES user_accounts(account_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id),
    FOREIGN KEY (cleaner_id) REFERENCES user_accounts(account_id)
);
