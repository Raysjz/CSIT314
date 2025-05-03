CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id SERIAL PRIMARY KEY,
    profile_name VARCHAR(50) NOT NULL,
    is_suspended BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS user_accounts (
    account_id SERIAL PRIMARY KEY,
    ua_username VARCHAR(50) UNIQUE NOT NULL,
    ua_password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    profile_name VARCHAR(20) NOT NULL,
    profile_id INT NOT NULL,
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (profile_id) REFERENCES user_profiles(profile_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS service_categories (
    category_id SERIAL PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS cleaner_services (
    service_id SERIAL PRIMARY KEY,
    cleaner_account_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    availability VARCHAR(100),
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
    viewer_account_id INT,
    FOREIGN KEY (service_id) REFERENCES cleaner_services(service_id) ON DELETE CASCADE,
    FOREIGN KEY (viewer_account_id) REFERENCES user_accounts(account_id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS service_shortlists (
    shortlist_id SERIAL PRIMARY KEY,
    homeowner_account_id INT NOT NULL,
    service_id INT NOT NULL,
    shortlisted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_confirmed BOOLEAN NOT NULL DEFAULT FALSE,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (homeowner_account_id) REFERENCES user_accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES cleaner_services(service_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS service_bookings (
    booking_id SERIAL PRIMARY KEY,
    shortlist_id INT NOT NULL,
    booking_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'confirmed',
    completed_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (shortlist_id) REFERENCES service_shortlists(shortlist_id) ON DELETE CASCADE
);

ALTER TABLE service_views ADD CONSTRAINT unique_service_view UNIQUE (service_id, viewer_account_id);
