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


-- Registros eliminados para evitar duplicación.
