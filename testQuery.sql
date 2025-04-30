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

/* Add For platform management

ALTER TABLE user_accounts
ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE user_accounts
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

*/
    
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

/*
Table Name	Purpose
cleaner_services	Cleaners create/manage their services
service_views	Track service views
service_shortlists	Homeowners shortlist services
service_bookings	Confirmed bookings (matches)
*/

CREATE TABLE IF NOT EXISTS cleaner_services (
    service_id SERIAL PRIMARY KEY,
    cleaner_account_id INT NOT NULL,  -- FK to user_accounts(account_id)
    category_id INT NOT NULL,         -- FK to service_categories(category_id)
    title VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    availability VARCHAR(100),        -- e.g., "Weekdays 9am-5pm"
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cleaner_account_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES service_categories(category_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS service_views (
    view_id SERIAL PRIMARY KEY,
    service_id INT NOT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    viewer_account_id INT,  -- Optional: track who viewed
    FOREIGN KEY (service_id) REFERENCES cleaner_services(service_id) ON DELETE CASCADE,
    FOREIGN KEY (viewer_account_id) REFERENCES user_accounts(account_id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS service_shortlists (
    shortlist_id SERIAL PRIMARY KEY,
    homeowner_account_id INT NOT NULL,  -- FK to user_accounts(account_id)
    service_id INT NOT NULL,            -- FK to cleaner_services(service_id)
    shortlisted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_confirmed BOOLEAN NOT NULL DEFAULT FALSE,  -- True if booking is confirmed
    FOREIGN KEY (homeowner_account_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES cleaner_services(service_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS service_bookings (
    booking_id SERIAL PRIMARY KEY,
    shortlist_id INT NOT NULL,           -- FK to service_shortlists(shortlist_id)
    booking_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'confirmed',  -- e.g., confirmed, completed, cancelled
    completed_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shortlist_id) REFERENCES service_shortlists(shortlist_id) ON DELETE CASCADE
);


-- Insert some example cleaner user accounts into the user_accounts table
INSERT INTO user_accounts (ua_username, ua_password, profile_name, profile_id, is_suspended)
VALUES
('cleaner_01', 'password123', 'Cleaner', 3, FALSE),
('cleaner_02', 'password123', 'Cleaner', 3, FALSE),
('cleaner_03', 'password123', 'Cleaner', 3, FALSE),
('cleaner_04', 'password123', 'Cleaner', 3, FALSE),
('cleaner_05', 'password123', 'Cleaner', 3, FALSE),
('cleaner_06', 'password123', 'Cleaner', 3, FALSE),
('cleaner_07', 'password123', 'Cleaner', 3, FALSE),
('cleaner_08', 'password123', 'Cleaner', 3, FALSE),
('cleaner_09', 'password123', 'Cleaner', 3, FALSE),
('cleaner_10', 'password123', 'Cleaner', 3, FALSE);

