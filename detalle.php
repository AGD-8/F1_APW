<?php
require_once 'functions.php';

$tipo = $_GET['tipo'] ?? '';
$id = $_GET['id'] ?? 0;

if (!$tipo || !$id) {
    header("Location: index.php");
    exit;
}

// Obtener datos del elemento
$table = ($tipo === 'circuito') ? 'circuitos' : (($tipo === 'piloto') ? 'pilotos' : 'vehiculos');
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$id]);
$elemento = $stmt->fetch();

if (!$elemento) {
    die("Elemento no encontrado.");
}

// Obtener valoraciones
$stmt = $pdo->prepare("SELECT v.*, u.usuario FROM valoraciones v JOIN usuarios u ON v.id_usuario = u.id WHERE v.tipo_elemento = ? AND v.id_elemento = ? ORDER BY v.fecha DESC");
$stmt->execute([$tipo, $id]);
$valoraciones = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $elemento['nombre']; ?> - Detalles</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-header { display: flex; gap: 40px; margin-top: 100px; margin-bottom: 50px; flex-wrap: wrap; }
        .detail-img { width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 0 30px rgba(0,0,0,0.5); }
        .detail-info { flex: 1; min-width: 300px; }
        .rating-box { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 20px; margin-top: 30px; }
        .valoracion-item { border-bottom: 1px solid rgba(255,255,255,0.1); padding: 15px 0; }
        .user-stars { color: #f1c40f; font-size: 1.2rem; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"><span class="logo-red">Motor</span><span class="logo-white">sport</span></div>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 120px;">
        <div class="detail-container">
            <img src="<?php echo $elemento['imagen_url']; ?>" class="detail-img-large" alt="Imagen">
            <div class="detail-info">
                <h1 style="font-size: 3.5rem; margin-bottom: 15px;"><?php echo $elemento['nombre']; ?></h1>
                <?php echo renderStars(getAverageRating($pdo, $tipo, $id)); ?>
                
                <div class="info-grid">
                    <?php if ($tipo === 'circuito'): ?>
                        <div class="info-tile"><span class="info-label">País</span><span class="info-value"><?php echo $elemento['pais']; ?></span></div>
                        <div class="info-tile"><span class="info-label">Longitud</span><span class="info-value"><?php echo $elemento['longitud_km']; ?> km</span></div>
                        <div class="info-tile"><span class="info-label">Inauguración</span><span class="info-value"><?php echo $elemento['anio_inauguracion'] ?? '1929'; ?></span></div>
                        <div class="info-tile"><span class="info-label">Capacidad</span><span class="info-value"><?php echo number_format($elemento['capacidad'] ?? 37000); ?></span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Vuelta Rápida</span><span class="info-value"><?php echo $elemento['vuelta_rapida'] ?? '1:10.166 (Lewis Hamilton)'; ?></span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Curvas Principales</span><span class="info-value"><?php echo nl2br(htmlspecialchars($elemento['curvas_principales'])); ?></span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Forma del Circuito</span><span class="info-value"><?php echo nl2br(htmlspecialchars($elemento['forma_circuito'])); ?></span></div>
                    
                    <?php elseif ($tipo === 'piloto'): ?>
                        <div class="info-tile"><span class="info-label">Nacionalidad</span><span class="info-value"><?php echo $elemento['nacionalidad']; ?></span></div>
                        <div class="info-tile"><span class="info-label">Dorsal</span><span class="info-value">#<?php echo $elemento['dorsal']; ?></span></div>
                        <div class="info-tile"><span class="info-label">Nacimiento</span><span class="info-value"><?php echo $elemento['anio_nacimiento']; ?></span></div>
                        <div class="info-tile"><span class="info-label">Títulos</span><span class="info-value"><?php echo $elemento['titulos']; ?> Mundiales</span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Equipo Actual</span><span class="info-value"><?php echo $elemento['equipo_actual'] ?? 'Oracle Red Bull Racing'; ?></span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Cualidades</span><span class="info-value"><?php echo nl2br(htmlspecialchars($elemento['cualidades'])); ?></span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Trayectoria</span><span class="info-value"><?php echo nl2br(htmlspecialchars($elemento['historia_equipos'] ?? 'Toro Rosso, Red Bull Racing')); ?></span></div>

                    <?php elseif ($tipo === 'vehiculo'): ?>
                        <div class="info-tile"><span class="info-label">Equipo</span><span class="info-value"><?php echo $elemento['equipo']; ?></span></div>
                        <div class="info-tile"><span class="info-label">Categoría</span><span class="info-value"><?php echo $elemento['categoria']; ?></span></div>
                        <div class="info-tile"><span class="info-label">Vel. Máxima</span><span class="info-value"><?php echo $elemento['velocidad_max'] ?? '350 km/h'; ?></span></div>
                        <div class="info-tile"><span class="info-label">Peso</span><span class="info-value"><?php echo $elemento['peso_kg'] ?? '798'; ?> kg</span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Motor</span><span class="info-value"><?php echo $elemento['motor']; ?></span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Aceleración</span><span class="info-value">0-100 en <?php echo $elemento['aceleracion_0_100'] ?? '2.4s'; ?></span></div>
                        <div class="info-tile" style="grid-column: span 2;"><span class="info-label">Neumáticos</span><span class="info-value"><?php echo $elemento['tipos_neumaticos']; ?></span></div>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="rating-box">
                        <h3>Valora este elemento</h3>
                        <form action="valorar.php" method="POST">
                            <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="star-rating" id="user-rating-input">
                                <input type="radio" name="estrellas" value="1" id="s1" required hidden><label for="s1" class="star">★</label>
                                <input type="radio" name="estrellas" value="2" id="s2" hidden><label for="s2" class="star">★</label>
                                <input type="radio" name="estrellas" value="3" id="s3" hidden><label for="s3" class="star">★</label>
                                <input type="radio" name="estrellas" value="4" id="s4" hidden><label for="s4" class="star">★</label>
                                <input type="radio" name="estrellas" value="5" id="s5" hidden><label for="s5" class="star">★</label>
                            </div>
                            <textarea name="comentario" placeholder="Escribe tu opinión..." style="width: 100%; height: 80px; margin-top: 10px;"></textarea>
                            <button type="submit" class="submit-btn" style="width: 100%; margin-top: 10px;">Enviar Valoración</button>
                        </form>
                    </div>
                    <div style="display: flex; gap: 20px; margin-top: 30px;">
                        <a href="index.php" class="submit-btn" style="background: #444; flex: 1; text-align: center;">← Volver al Inicio</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="eliminar.php?tipo=<?php echo $tipo; ?>&id=<?php echo $id; ?>" 
                               class="submit-btn" 
                               style="background: #e00606; flex: 1; text-align: center;"
                               onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento de UltraSpeed?')">🗑 Eliminar Registro</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p style="margin-top: 20px;"><a href="login.php">Inicia sesión</a> para valorar este elemento.</p>
                    <a href="index.php" class="submit-btn" style="background: #444; display: block; text-align: center; margin-top: 20px;">← Volver al Inicio</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Opiniones Recientes</h2>
            <?php foreach ($valoraciones as $val): ?>
                <div class="valoracion-item">
                    <div style="display: flex; justify-content: space-between;">
                        <strong><?php echo htmlspecialchars($val['usuario']); ?></strong>
                        <span class="user-stars">
                            <?php for($i=1; $i<=5; $i++) echo ($i <= $val['estrellas'] ? '★' : '☆'); ?>
                        </span>
                    </div>
                    <p style="margin-top: 10px;"><?php echo nl2br(htmlspecialchars($val['comentario'])); ?></p>
                    <small style="color: var(--text-muted);"><?php echo $val['fecha']; ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Simple star rating selection logic
        const stars = document.querySelectorAll('#user-rating-input label');
        stars.forEach((star, index) => {
            star.onclick = () => {
                stars.forEach((s, i) => {
                    if (i <= index) s.classList.add('filled');
                    else s.classList.remove('filled');
                });
            }
        });
    </script>
</body>
</html>
