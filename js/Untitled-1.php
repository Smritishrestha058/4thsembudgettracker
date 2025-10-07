CREATE TABLE ContactMessages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE UserDetails ADD COLUMN is_admin TINYINT(1) DEFAULT 0;

UPDATE UserDetails SET is_admin = 1 WHERE email = 'admin@example.com';
