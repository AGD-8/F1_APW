<?php
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$tipo = $_GET['tipo'] ?? $_POST['tipo'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? 0;

if (!$tipo || !$id) {
    header("Location: index.php");
    exit;
}

$table = ($tipo === 'circuito') ? 'circuitos' : (($tipo === 'piloto') ? 'pilotos' : 'vehiculos');

// Manejar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $imagen_url = $_POST['imagen_url'] ?? '';

    if ($tipo === 'circuito') {
        $stmt = $pdo->prepare("UPDATE circuitos SET nombre = ?, pais = ?, longitud_km = ?, imagen_url = ?, curvas_principales = ?, forma_circuito = ?, vuelta_rapida = ?, anio_inauguracion = ?, capacidad = ? WHERE id = ?");
        $stmt->execute([
            $nombre, $_POST['pais'], $_POST['longitud'], $imagen_url, $_POST['curvas'], $_POST['forma'], $_POST['vuelta_rapida'], $_POST['anio_inauguracion'], $_POST['capacidad'], $id
        ]);
    } elseif ($tipo === 'piloto') {
        $stmt = $pdo->prepare("UPDATE pilotos SET nombre = ?, nacionalidad = ?, dorsal = ?, imagen_url = ?, anio_nacimiento = ?, cualidades = ?, titulos = ?, equipo_actual = ?, historia_equipos = ? WHERE id = ?");
        $stmt->execute([
            $nombre, $_POST['nacionalidad'], $_POST['dorsal'], $imagen_url, $_POST['anio'], $_POST['cualidades'], $_POST['titulos'], $_POST['equipo_actual'], $_POST['historia_equipos'], $id
        ]);
    } elseif ($tipo === 'vehiculo') {
        $stmt = $pdo->prepare("UPDATE vehiculos SET nombre = ?, equipo = ?, categoria = ?, imagen_url = ?, tipos_neumaticos = ?, motor = ?, velocidad_max = ?, aceleracion_0_100 = ?, peso_kg = ? WHERE id = ?");
        $stmt->execute([
            $nombre, $_POST['equipo'], $_POST['categoria_veh'], $imagen_url, $_POST['neumaticos'], $_POST['motor'], $_POST['velocidad_max'], $_POST['aceleracion_0_100'], $_POST['peso_kg'], $id
        ]);
    }
    
    header("Location: detalle.php?tipo=$tipo&id=$id");
    exit;
}

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$id]);
$elemento = $stmt->fetch();

if (!$elemento) {
    die("Elemento no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar <?php echo htmlspecialchars($elemento['nombre']); ?> - UltraSpeed</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .edit-container {
            margin-top: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            margin-bottom: 80px;
        }
        .preview-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-top: 15px;
            border: 1px solid var(--primary);
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        }
        textarea.edit-textarea {
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            color: white;
            padding: 14px;
            font-family: inherit;
            resize: vertical;
        }
        textarea.edit-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 15px var(--glow-f1);
            outline: none;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"><span class="logo-ultra">ULTRA</span><span class="logo-speed">SPEED</span></div>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="detalle.php?tipo=<?php echo $tipo; ?>&id=<?php echo $id; ?>">Volver al Detalle</a></li>
            </ul>
        </div>
    </nav>

    <div class="edit-container">
        <div class="form-header">
            <h2>Editar Registro</h2>
            <p>Modifica los datos de <?php echo htmlspecialchars($elemento['nombre']); ?></p>
        </div>

        <form action="editar.php" method="POST" class="glass-form">
            <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-group">
                <label>Nombre / Título</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($elemento['nombre']); ?>" required>
            </div>
            
            <div class="input-group">
                <label>URL de Imagen</label>
                <input type="text" name="imagen_url" id="imagen_url" value="<?php echo htmlspecialchars($elemento['imagen_url']); ?>" required>
                <img src="<?php echo htmlspecialchars($elemento['imagen_url']); ?>" id="preview_img" class="preview-img" alt="Vista previa">
            </div>

            <?php if ($tipo === 'circuito'): ?>
                <div class="input-row">
                    <div class="input-group half-width"><label>País</label><input type="text" name="pais" value="<?php echo htmlspecialchars($elemento['pais']); ?>"></div>
                    <div class="input-group half-width"><label>Longitud (km)</label><input type="number" step="0.01" name="longitud" value="<?php echo htmlspecialchars($elemento['longitud_km']); ?>"></div>
                </div>
                <div class="input-row">
                    <div class="input-group half-width"><label>Año de Inauguración</label><input type="number" name="anio_inauguracion" value="<?php echo htmlspecialchars($elemento['anio_inauguracion'] ?? ''); ?>"></div>
                    <div class="input-group half-width"><label>Capacidad</label><input type="number" name="capacidad" value="<?php echo htmlspecialchars($elemento['capacidad'] ?? ''); ?>"></div>
                </div>
                <div class="input-group"><label>Vuelta Rápida</label><input type="text" name="vuelta_rapida" value="<?php echo htmlspecialchars($elemento['vuelta_rapida'] ?? ''); ?>"></div>
                <div class="input-group"><label>Curvas Principales</label><textarea name="curvas" rows="3" class="edit-textarea"><?php echo htmlspecialchars($elemento['curvas_principales']); ?></textarea></div>
                <div class="input-group"><label>Forma del Circuito (Descripción)</label><textarea name="forma" rows="3" class="edit-textarea"><?php echo htmlspecialchars($elemento['forma_circuito']); ?></textarea></div>

            <?php elseif ($tipo === 'piloto'): ?>
                <div class="input-row">
                    <div class="input-group half-width"><label>Nacionalidad</label><input type="text" name="nacionalidad" value="<?php echo htmlspecialchars($elemento['nacionalidad']); ?>"></div>
                    <div class="input-group half-width"><label>Dorsal</label><input type="number" name="dorsal" value="<?php echo htmlspecialchars($elemento['dorsal']); ?>"></div>
                </div>
                <div class="input-row">
                    <div class="input-group half-width"><label>Año Nacimiento</label><input type="number" name="anio" value="<?php echo htmlspecialchars($elemento['anio_nacimiento']); ?>"></div>
                    <div class="input-group half-width"><label>Títulos</label><input type="number" name="titulos" value="<?php echo htmlspecialchars($elemento['titulos']); ?>"></div>
                </div>
                <div class="input-group"><label>Equipo Actual</label><input type="text" name="equipo_actual" value="<?php echo htmlspecialchars($elemento['equipo_actual'] ?? ''); ?>"></div>
                <div class="input-group"><label>Cualidades</label><textarea name="cualidades" rows="3" class="edit-textarea"><?php echo htmlspecialchars($elemento['cualidades']); ?></textarea></div>
                <div class="input-group"><label>Trayectoria</label><textarea name="historia_equipos" rows="3" class="edit-textarea"><?php echo htmlspecialchars($elemento['historia_equipos'] ?? ''); ?></textarea></div>

            <?php elseif ($tipo === 'vehiculo'): ?>
                <div class="input-row">
                    <div class="input-group half-width"><label>Equipo</label><input type="text" name="equipo" value="<?php echo htmlspecialchars($elemento['equipo']); ?>"></div>
                    <div class="input-group half-width">
                        <label>Categoría</label>
                        <select name="categoria_veh">
                            <option value="Formula 1" <?php echo ($elemento['categoria'] === 'Formula 1') ? 'selected' : ''; ?>>Formula 1</option>
                            <option value="MotoGP" <?php echo ($elemento['categoria'] === 'MotoGP') ? 'selected' : ''; ?>>MotoGP</option>
                        </select>
                    </div>
                </div>
                <div class="input-row">
                    <div class="input-group half-width"><label>Vel. Máxima</label><input type="text" name="velocidad_max" value="<?php echo htmlspecialchars($elemento['velocidad_max'] ?? ''); ?>"></div>
                    <div class="input-group half-width"><label>Peso (kg)</label><input type="number" name="peso_kg" value="<?php echo htmlspecialchars($elemento['peso_kg'] ?? ''); ?>"></div>
                </div>
                <div class="input-group"><label>Motor</label><input type="text" name="motor" value="<?php echo htmlspecialchars($elemento['motor']); ?>"></div>
                <div class="input-group"><label>Aceleración 0-100</label><input type="text" name="aceleracion_0_100" value="<?php echo htmlspecialchars($elemento['aceleracion_0_100'] ?? ''); ?>"></div>
                <div class="input-group"><label>Neumáticos</label><input type="text" name="neumaticos" value="<?php echo htmlspecialchars($elemento['tipos_neumaticos']); ?>"></div>
            <?php endif; ?>

            <div style="display: flex; gap: 20px; margin-top: 20px;">
                <a href="detalle.php?tipo=<?php echo $tipo; ?>&id=<?php echo $id; ?>" class="submit-btn" style="background: #444; flex: 1; text-align: center; text-decoration: none;">Cancelar</a>
                <button type="submit" class="submit-btn" style="flex: 2; background: linear-gradient(90deg, #2b78ff, #00e5ff); color: white;">Guardar Cambios</button>
            </div>
        </form>
    </div>

    <script>
        const inputUrl = document.getElementById('imagen_url');
        const previewImg = document.getElementById('preview_img');

        inputUrl.addEventListener('input', () => {
            if(inputUrl.value.trim() !== "") {
                previewImg.src = inputUrl.value;
                previewImg.style.display = 'block';
            } else {
                previewImg.style.display = 'none';
            }
        });
    </script>
</body>
</html>
