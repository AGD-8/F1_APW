-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS motorsport_db;
USE motorsport_db;

-- Tabla 1: circuitos
-- Almacena la información de los trazados donde corren Formula 1 y MotoGP
CREATE TABLE IF NOT EXISTS circuitos (
    id_circuito INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    longitud_km DECIMAL(5,2) NOT NULL
);

-- Tabla 2: carreras
-- Almacena los eventos de carrera, relacionando el circuito y registrando el piloto ganador con su coche o moto
CREATE TABLE IF NOT EXISTS carreras (
    id_carrera INT AUTO_INCREMENT PRIMARY KEY,
    id_circuito INT NOT NULL,
    categoria ENUM('Formula 1', 'MotoGP') NOT NULL,
    piloto_ganador VARCHAR(100) NOT NULL,
    vehiculo_utilizado VARCHAR(100) NOT NULL, -- Aquí indicamos el coche o la moto
    anio INT NOT NULL,
    FOREIGN KEY (id_circuito) REFERENCES circuitos(id_circuito) ON DELETE CASCADE
);

-- Inserción de registros de ejemplo en la tabla circuitos
INSERT INTO circuitos (nombre, pais, longitud_km) VALUES
('Circuito de Mónaco', 'Mónaco', 3.34),
('Circuito de Silverstone', 'Reino Unido', 5.89),
('Circuito de Jerez - Ángel Nieto', 'España', 4.42),
('Circuito de Mugello', 'Italia', 5.25),
('Circuito de Spa-Francorchamps', 'Bélgica', 7.00);

-- Inserción de registros de ejemplo en la tabla carreras (relacionados con circuitos por id_circuito)
INSERT INTO carreras (id_circuito, categoria, piloto_ganador, vehiculo_utilizado, anio) VALUES
(1, 'Formula 1', 'Max Verstappen', 'Red Bull RB19 (Coche)', 2023),
(2, 'Formula 1', 'Carlos Sainz', 'Ferrari F1-75 (Coche)', 2022),
(3, 'MotoGP', 'Marc Márquez', 'Honda RC213V (Moto)', 2019),
(4, 'MotoGP', 'Francesco Bagnaia', 'Ducati Desmosedici GP23 (Moto)', 2023),
(5, 'Formula 1', 'Lewis Hamilton', 'Mercedes AMG F1 W11 (Coche)', 2020);
