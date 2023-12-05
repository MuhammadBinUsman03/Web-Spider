CREATE DATABASE IF NOT EXISTS crawling;
USE crawling;
CREATE TABLE crawled_pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    title VARCHAR(255),
    html_content TEXT NOT NULL,
    Description TEXT
);
