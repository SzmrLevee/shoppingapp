DROP DATABASE IF EXISTS shoppingapp;
CREATE DATABASE shoppingapp;
USE shoppingapp;

CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    firstName CHAR(40) NOT NULL,
    lastName CHAR(40) NOT NULL,
    email CHAR(40) NOT NULL UNIQUE,
    password CHAR(40) NOT NULL,
    role INT(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS todos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    checked TINYINT(1) DEFAULT 0,
    user_id INT(11),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (id, firstName, lastName, email, password, role)
VALUES (
    1, 
    'Levente', 
    'Szomor', 
    'admin@admin.com', 
    sha1("jelszo"),
    4
);