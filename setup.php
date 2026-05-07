<?php
/**
 * setup.php - Versión Robusta
 * Crea la base de datos y las tablas automáticamente.
 */

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // 1. Conectar al servidor sin seleccionar base de datos
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 2. Crear la base de datos si no existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS motorsport_full_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE motorsport_full_db");
    
    // 3. Leer y ejecutar el archivo SQL
    $sqlFile = 'full_schema.sql';
    if (!file_exists($sqlFile)) {
        die("Error: No se encuentra el archivo $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);
    
    echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 20px; background: #f0fff4; border: 1px solid #c6f6d5; color: #22543d; box-shadow: 0 10px 25px rgba(0,0,0,0.05);'>";
    echo "<h2 style='margin-top: 0;'>🚀 ¡Todo listo!</h2>";
    echo "<p>La base de datos <strong>motorsport_full_db</strong> y todas sus tablas han sido creadas con éxito.</p>";
    echo "<ul style='padding-left: 20px;'>";
    echo "<li>Tablas de usuarios creadas.</li>";
    echo "<li>Sistema de valoraciones listo.</li>";
    echo "<li>Chat de comunidad activado.</li>";
    echo "</ul>";
    echo "<hr style='border: 0; border-top: 1px solid #c6f6d5; margin: 20px 0;'>";
    echo "<p>Redirigiendo al inicio en 3 segundos...</p>";
    echo "<a href='index.php' style='display: inline-block; padding: 12px 24px; background: #38a169; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; transition: background 0.3s;'>Comenzar ahora</a>";
    echo "</div>";
    header("Refresh: 3; url=index.php");

} catch (PDOException $e) {
    echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 20px; background: #fff5f5; border: 1px solid #fed7d7; color: #822727;'>";
    echo "<h2>Error Fatal</h2>";
    echo "<p>Hubo un problema al configurar el sistema:</p>";
    echo "<pre style='background: #fff; padding: 10px; border-radius: 5px; overflow-x: auto;'>" . $e->getMessage() . "</pre>";
    echo "</div>";
}
?>
