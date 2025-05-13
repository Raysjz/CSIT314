-- Stores different user roles (Admin, Homeowner, Cleaner, etc.)
CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id SERIAL PRIMARY KEY,                  -- Unique profile ID
    profile_name VARCHAR(50) NOT NULL,              -- Name of the profile
    is_suspended BOOLEAN NOT NULL                   -- Suspension status
);

-- Stores login and identity details for all users
CREATE TABLE IF NOT EXISTS user_accounts (
    account_id SERIAL PRIMARY KEY,                  -- Unique account ID
    ua_username VARCHAR(50) UNIQUE NOT NULL,        -- Unique username
    ua_password VARCHAR(255) NOT NULL,              -- Password (encrypted or plain for testing)
    full_name VARCHAR(100) NOT NULL,                -- User's full name
    email VARCHAR(100) UNIQUE NOT NULL,             -- Unique email address
    profile_name VARCHAR(20) NOT NULL,              -- Redundant; ideally derived via join from user_profiles
    profile_id INT NOT NULL,                        -- Link to user_profiles
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE,    -- Suspension status
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Account creation timestamp
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Last update timestamp
    FOREIGN KEY (profile_id) REFERENCES user_profiles(profile_id) ON DELETE CASCADE
);

-- Stores cleaning service categories
CREATE TABLE IF NOT EXISTS service_categories (
    category_id SERIAL PRIMARY KEY,                 -- Unique category ID
    category_name VARCHAR(255) NOT NULL,            -- Category name (e.g., Deep Cleaning)
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE     -- Suspension status
);

-- Stores services posted by cleaners
CREATE TABLE IF NOT EXISTS cleaner_services (
    service_id SERIAL PRIMARY KEY,                  -- Unique service ID
    cleaner_account_id INT NOT NULL,                -- Link to user_accounts (Cleaner)
    category_id INT NOT NULL,                       -- Link to service_categories
    title VARCHAR(100) NOT NULL,                    -- Title of the service
    description TEXT,                               -- Description of service
    price DECIMAL(10,2) NOT NULL,                   -- Price of the service
    availability VARCHAR(100),                      -- Available time slots
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE,    -- Suspension status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Created timestamp
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Last update timestamp
    FOREIGN KEY (cleaner_account_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES service_categories(category_id) ON DELETE CASCADE
); 

-- Logs views of a cleaner's service (analytics)
CREATE TABLE IF NOT EXISTS service_views (
    view_id SERIAL PRIMARY KEY,                     -- Unique view ID
    service_id INT NOT NULL,                        -- Link to cleaner_services
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- When it was viewed
    viewer_account_id INT,                          -- Who viewed (optional)
    FOREIGN KEY (service_id) REFERENCES cleaner_services(service_id) ON DELETE CASCADE,
    FOREIGN KEY (viewer_account_id) REFERENCES user_accounts(account_id) ON DELETE SET NULL,
    CONSTRAINT unique_service_view UNIQUE (service_id, viewer_account_id)
);

-- Stores shortlisted services by homeowners
CREATE TABLE IF NOT EXISTS service_shortlists (
    shortlist_id SERIAL PRIMARY KEY,                -- Unique shortlist ID
    homeowner_account_id INT NOT NULL,              -- Link to user_accounts (Homeowner)
    service_id INT NOT NULL,                        -- Link to cleaner_services
    shortlisted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Time of shortlisting
    is_confirmed BOOLEAN NOT NULL DEFAULT FALSE,    -- Whether the service was confirmed/booked
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,      -- Flag if removed
    FOREIGN KEY (homeowner_account_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES cleaner_services(service_id) ON DELETE CASCADE
);

-- Finalized bookings between homeowner and cleaner
CREATE TABLE IF NOT EXISTS matching_bookings (
    match_id SERIAL PRIMARY KEY,                    -- Unique booking ID
    homeowner_account_id INT NOT NULL,              -- Link to user_accounts (Homeowner)
    cleaner_account_id INT NOT NULL,                -- Link to user_accounts (Cleaner)
    service_id INT NOT NULL,                        -- Link to cleaner_services
    category_id INT NOT NULL,                       -- Link to service_categories
    booking_date DATE NOT NULL,                     -- Date of the appointment
    status VARCHAR(20) NOT NULL DEFAULT 'confirmed',-- Booking status
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Creation time
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,      -- Deletion flag
    FOREIGN KEY (homeowner_account_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (cleaner_account_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES cleaner_services(service_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES service_categories(category_id) ON DELETE CASCADE
);
