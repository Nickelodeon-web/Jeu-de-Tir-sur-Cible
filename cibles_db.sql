CREATE DATABASE IF NOT EXISTS cibles_db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE cibles_db;

CREATE TABLE IF NOT EXISTS regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO regions (name) VALUES 
('Auvergne-Rhône-Alpes'), 
('Bourgogne-Franche-Comté'), 
('Bretagne'), 
('Centre-Val de Loire'), 
('Corse'), 
('Grand Est'), 
('Hauts-de-France'), 
('Île-de-France'), 
('Normandie'), 
('Nouvelle-Aquitaine'), 
('Occitanie'), 
('Pays de la Loire'), 
('Provence-Alpes-Côte d''Azur');

CREATE TABLE IF NOT EXISTS players (
    pseudo VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    registration_date DATETIME NOT NULL,
    region_id INT NOT NULL,
    FOREIGN KEY (region_id) REFERENCES regions(id)
);

INSERT INTO players (pseudo, password, email, birthdate, registration_date, region_id) VALUES 
('Legolas', '$2y$10$H8pVXEt3Ru80/kGvUkAsQuD54r/pdcvrbS5fFT4x95vngjCG3WDDi', 'legolas@mirkwood.com', '1990-01-01', NOW(), 1),
('RobinHood', '$2y$10$H8pVXEt3Ru80/kGvUkAsQuD54r/pdcvrbS5fFT4x95vngjCG3WDDi', 'robin@sherwood.com', '1992-05-15', NOW(), 6),
('Katniss', '$2y$10$H8pVXEt3Ru80/kGvUkAsQuD54r/pdcvrbS5fFT4x95vngjCG3WDDi', 'katniss@district12.com', '2000-05-08', NOW(), 13);
