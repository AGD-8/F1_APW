-- full_schema.sql
CREATE DATABASE IF NOT EXISTS motorsport_full_db;
USE motorsport_full_db;

-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    foto_perfil VARCHAR(255) DEFAULT 'assets/default_user.png',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Circuitos
CREATE TABLE IF NOT EXISTS circuitos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    pais VARCHAR(50),
    longitud_km DECIMAL(5,2),
    imagen_url VARCHAR(255),
    curvas_principales TEXT,
    forma_circuito TEXT, 
    vuelta_rapida VARCHAR(50),
    anio_inauguracion INT,
    capacidad INT,
    creado_por INT,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);

-- Tabla de Pilotos
CREATE TABLE IF NOT EXISTS pilotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    nacionalidad VARCHAR(50),
    dorsal INT,
    imagen_url VARCHAR(255),
    anio_nacimiento INT,
    cualidades TEXT, 
    titulos INT DEFAULT 0,
    equipo_actual VARCHAR(100),
    historia_equipos TEXT,
    creado_por INT,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);

-- Tabla de Vehículos
CREATE TABLE IF NOT EXISTS vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    equipo VARCHAR(100),
    categoria ENUM('Formula 1', 'MotoGP'),
    imagen_url VARCHAR(255),
    tipos_neumaticos TEXT,
    motor VARCHAR(100),
    velocidad_max VARCHAR(20),
    aceleracion_0_100 VARCHAR(20),
    peso_kg INT,
    creado_por INT,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);

-- Tabla de Valoraciones (Estrellas)
CREATE TABLE IF NOT EXISTS valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    tipo_elemento ENUM('circuito', 'vehiculo', 'piloto'),
    id_elemento INT,
    estrellas INT CHECK (estrellas BETWEEN 1 AND 5),
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de Mensajes (Chat)
CREATE TABLE IF NOT EXISTS mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    tipo_elemento ENUM('circuito', 'vehiculo', 'piloto'),
    id_elemento INT,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);


-- La base de datos se inicializa vacía. 
-- Los elementos deben añadirse a través de los formularios de la aplicación.

