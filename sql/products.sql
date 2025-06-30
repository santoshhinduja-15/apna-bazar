CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    description TEXT,
    image VARCHAR(255), -- Store the filename or path
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
