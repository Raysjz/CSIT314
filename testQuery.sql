/*
User Accounts 
*/
CREATE TABLE user_accounts (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    profile VARCHAR(20) NOT NULL,
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE
);

    
INSERT INTO user_accounts (username, password, profile, is_suspended)
VALUES
('admin', '1234', 'User Admin', FALSE),
('homeowner', '1234', 'Home Owner', FALSE),
('cleaner', '1234', 'Cleaner', FALSE),
('platformmgmt', '1234', 'Platform Management', FALSE);
('admin', '1111', 'User Admin', TRUE);
('cleaner2', '5654', 'Cleaner', TRUE);

drop table user_accounts;

select * from public.user_accounts;

/*
User Profile
*/

CREATE TABLE user_profiles (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    profile VARCHAR(20) NOT NULL,
    is_suspended BOOLEAN NOT NULL DEFAULT FALSE
);