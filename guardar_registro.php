<?php
/**
 * guardar_registro.php
 * Procesa el formulario de registro y guarda los datos en la tabla 'Registros'
 */
require_once 'config.php';

// Inicializamos variables para el mensaje
$mensaje = "";
$clase_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos y saneamos los datos básicos
    $categoria = $_POST['category'] ?? '';
    $nombre = $_POST['name'] ?? '';
    $imagen_url = $_POST['image'] ?? '';
    
    // Variables adicionales según la categoría
    $pais = '';
    $dorsal_longitud = '';

    // Lógica para mapear los campos dinámicos del formulario a las columnas de la BD
    if ($categoria === 'circuito') {
        $pais = $_POST['country'] ?? '';
        $dorsal_longitud = $_POST['length'] ?? '';
    } elseif ($categoria === 'vehiculo') {
        $pais = $_POST['team'] ?? ''; // El equipo se guarda en la columna 'pais' para esta tabla unificada
        $dorsal_longitud = '';
    } elseif ($categoria === 'piloto') {
        $pais = $_POST['country'] ?? '';
        $dorsal_longitud = $_POST['number'] ?? '';
    }

    // Validación básica
    if (empty($categoria) || empty($nombre)) {
        $mensaje = "Error: El nombre y la categoría son obligatorios.";
        $clase_mensaje = "error";
    } else {
        try {
            // Consulta preparada para evitar SQL Injection
            $sql = "INSERT INTO Registros (categoria, nombre, pais, dorsal_longitud, imagen_url) 
                    VALUES (:categoria, :nombre, :pais, :dorsal_longitud, :imagen_url)";
            
            $stmt = $pdo->prepare($sql);
            
            $resultado = $stmt->execute([
                ':categoria' => $categoria,
                ':nombre'    => $nombre,
                ':pais'      => $pais,
                ':dorsal_longitud' => $dorsal_longitud,
                ':imagen_url' => $imagen_url
            ]);

            if ($resultado) {
                // Si todo sale bien, redirigimos con un mensaje de éxito
                header('Location: ver_registros.php?msg=success');
                exit;
            } else {
                $mensaje = "Error: No se pudo completar el registro.";
                $clase_mensaje = "error";
            }

        } catch (PDOException $e) {
            $mensaje = "Error en la base de datos: " . $e->getMessage();
            $clase_mensaje = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado del Registro - Motorsport Hub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .status-container {
            margin-top: 150px;
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .error { color: #ff4b38; }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 30px;
        }
    </style>
</head>
<body>
    <div class="status-container">
        <h2 class="<?php echo $clase_mensaje; ?>"><?php echo $mensaje; ?></h2>
        <a href="index.html" class="back-btn">Volver al Inicio</a>
    </div>
</body>
</html>
