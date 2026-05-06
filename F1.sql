-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS motorsport_db;
USE motorsport_db;

-- Tabla 1: circuitos
CREATE TABLE IF NOT EXISTS circuitos (
    id_circuito INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    longitud_km DECIMAL(5,2) NOT NULL,
    imagen_url VARCHAR(255)
);

-- Tabla 2: vehiculos (Incluye coches F1 y motos MotoGP)
CREATE TABLE IF NOT EXISTS vehiculos (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    equipo VARCHAR(100) NOT NULL,
    nombre_vehiculo VARCHAR(100) NOT NULL,
    categoria ENUM('Formula 1', 'MotoGP') NOT NULL,
    imagen_url VARCHAR(255)
);

-- Tabla 3: pilotos
CREATE TABLE IF NOT EXISTS pilotos (
    id_piloto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL,
    dorsal INT,
    id_vehiculo INT,
    imagen_url VARCHAR(255),
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id_vehiculo) ON DELETE SET NULL
);

-- Inserción de registros de ejemplo
INSERT INTO circuitos (nombre, pais, longitud_km, imagen_url) VALUES
('Circuito de Mónaco', 'Mónaco', 3.34, 'assets/circuito_monaco.png'),
('Circuito de Silverstone', 'Reino Unido', 5.89, 'assets/circuito_silverstone.png');

INSERT INTO vehiculos (equipo, nombre_vehiculo, categoria, imagen_url) VALUES
('Red Bull Racing', 'Red Bull RB19', 'Formula 1', 'assets/f1_bolido.png'),
('Ducati Lenovo Team', 'Desmosedici GP23', 'MotoGP', 'assets/motogp_moto.png');

INSERT INTO pilotos (nombre, nacionalidad, dorsal, id_vehiculo, imagen_url) VALUES
('Max Verstappen', 'Países Bajos', 1, 1, 'assets/piloto_max.png'),
('Francesco Bagnaia', 'Italia', 1, 2, 'assets/piloto_pecco.png');
