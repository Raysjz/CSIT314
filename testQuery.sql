-- User Profile
CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id SERIAL PRIMARY KEY,    -- Primary Key for user_profiles
    name VARCHAR(50) NOT NULL,         -- Profile name (e.g., Homeowner, Cleaner)
    is_suspended BOOLEAN NOT NULL      -- Profile suspension status
);

-- User Account
CREATE TABLE IF NOT EXISTS user_accounts (
    account_id SERIAL PRIMARY KEY,        -- Primary Key for user_accounts
    username VARCHAR(50) UNIQUE NOT NULL, -- Username (unique)
    password VARCHAR(255) NOT NULL,       -- Password
    profile VARCHAR(20) NOT NULL,         -- Profile name (Admin, Homeowner, Cleaner, etc.)
    profile_id INT NOT NULL,              -- Foreign key to user_profiles table
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE,  -- Account suspension status
    FOREIGN KEY (profile_id) REFERENCES user_profiles(profile_id) ON DELETE CASCADE -- Link to user_profiles
);
    
-- Insert profiles into user_profiles
INSERT INTO user_profiles (name, is_suspended)
VALUES
('User Admin', FALSE),
('Homeowner', FALSE),
('Cleaner', FALSE),
('Platform Management', FALSE);

-- Insert users into user_accounts with profile_id
INSERT INTO user_accounts (username, password, profile, profile_id, is_suspended)
VALUES
('admin', '1234', 'User Admin', 1, FALSE),
('homeowner', '1234', 'Homeowner', 2, FALSE),
('cleaner', '1234', 'Cleaner', 3, FALSE),	
('platformmgmt', '1234', 'Platform Management', 4, FALSE);


select * from user_accounts;
-- drop table user_accounts;
select * from user_profiles;
-- drop table user_profiles;
SELECT column_name
FROM information_schema.columns
WHERE table_name = 'user_accounts';