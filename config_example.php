<?php
/**
 * config_example.php
 * 
 * Plantilla de ejemplo para la configuración de conexión a la base de datos de UltraSpeed.
 * Para utilizarla, realiza una copia de este archivo, cámbiale el nombre a "config.php" y
 * completa las credenciales correspondientes a tu servidor MySQL local.
 * 
 * NOTA: El archivo "config.php" está registrado en el ".gitignore" por motivos de seguridad,
 * por lo que tus credenciales de base de datos reales NUNCA se subirán al repositorio de Git.
 */

// Host o Servidor de Base de Datos
$host = 'tu_servidor_mysql'; // Ej: 'localhost' o '127.0.0.1'

// Nombre de la base de datos
$db   = 'nombre_de_tu_base_de_datos'; // Ej: 'motorsport_full_db'

// Usuario de conexión a MySQL
$user = 'tu_usuario_mysql'; // Ej: 'root'

// Contraseña del usuario MySQL
$pass = 'tu_contrasena_mysql'; // Ej: '' (vacío en XAMPP estándar) o tu contraseña real

// Codificación de caracteres
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Error de conexión: " . $e->getMessage());
}

// Inicialización automática de la sesión para todo el sitio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
