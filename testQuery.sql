CREATE TABLE useraccount (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL, 
    profile VARCHAR(20) NOT NULL,
	isSuspended BOOLEAN NOT NULL   -- Use BOOLEAN for suspension status
);

    
INSERT INTO useraccount (username, password, profile, isSuspended)
VALUES
('admin', '1234', 'User Admin', FALSE),
('homeowner', '1234', 'Home Owner', FALSE),
('cleaner', '1234', 'Cleaner', FALSE),
('platformmgmt', '1234', 'Platform Management', FALSE);
