<?php
// ver_registros.php
require_once 'config.php';

try {
    // Consulta para obtener todos los registros
    $stmt = $pdo->query("SELECT * FROM Registros");
    $registros = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error al cargar los registros: " . $e->getMessage();
}

// Comprobamos si hay mensajes de éxito en la URL
$msg_exito = "";
if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $msg_exito = "¡Registro guardado correctamente!";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registros - Motorsport Hub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .table-container {
            margin-top: 100px;
            padding: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        th {
            background: rgba(255, 255, 255, 0.05);
            color: var(--primary);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }
        .img-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <span class="logo-red">Motor</span><span class="logo-white">sport</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.html">Inicio</a></li>
            </ul>
        </div>
    </nav>

    <div class="container table-container">
        <a href="index.html" class="back-link">← Volver al Inicio</a>
        <h2 class="section-title">Listado de Registros</h2>
        
        <?php if ($msg_exito): ?>
            <div style="background: rgba(0, 255, 100, 0.1); color: #00ff64; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid rgba(0, 255, 100, 0.2);">
                <?php echo $msg_exito; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p style="color: #ff4b38;"><?php echo $error; ?></p>
        <?php elseif (empty($registros)): ?>
            <p>No hay registros disponibles en este momento.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoría</th>
                        <th>Nombre</th>
                        <th>País/Nac.</th>
                        <th>Dorsal/Long.</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id'] ?? $row['id_registro'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['categoria'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['pais'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['dorsal_longitud'] ?? '-'); ?></td>
                            <td>
                                <?php if (!empty($row['imagen_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['imagen_url']); ?>" class="img-preview" alt="Imagen">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
