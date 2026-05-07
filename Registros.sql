USE motorsport_db;

CREATE TABLE IF NOT EXISTS Registros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria ENUM('circuito', 'vehiculo', 'piloto') NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    pais VARCHAR(100),
    dorsal_longitud VARCHAR(50),
    imagen_url VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
