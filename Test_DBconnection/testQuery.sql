CREATE TABLE IF NOT EXISTS products (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                image VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );


/*
May need to add suspended and IsUserAdmin column for furth clarifications


                    <option value="">-- Select Profile --</option>
                    <option value="User Admin">User Admin</option>
                    <option value="Home Owner">Home Owner</option>
                    <option value="Cleaner">Cleaner</option>
                    <option value="Platform Management">Platform Management</option>

*/

CREATE TABLE useraccount (
    id SERIAL PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    address TEXT,
    password TEXT NOT NULL,
    role VARCHAR(20) NOT NULL
);



CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    profile VARCHAR(50) NOT NULL,
    isUserAdmin VARCHAR(50) NOT NULL,
    isSuspended VARCHAR(50) NOT NULL
);

/* Test Data */

INSERT INTO users(id,username,password,profile,isUserAdmin,isSuspended)
VALUES
(1,'admin','1234','User Admin','Yes','No');
(2,'homeowner','1234','Home Owner','No','No');
(3, 'cleaner', '1234', 'Cleaner', 'No', 'No'),
(4, 'platformmgmt', '1234', 'Platform Management', 'No', 'No');


INSERT INTO products (name, price, image)
VALUES 
('Premium Headphones', 99.00, 'https://www.apple.com/sg/shop/product/MQTR3PA/A/beats-studio-pro-wireless-headphones-sandstone'),
('Nothing Ear Noise Cancelling True Wireless Earphones', 199.00, 'https://www.stereo.com.sg/cdn/shop/files/9072024_42747_pm_nothing_1__1_1.jpg?v=1726777011&width=600');
