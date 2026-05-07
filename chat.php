<?php
require_once 'config.php';

$tipo = $_GET['tipo'] ?? '';
$id = $_GET['id'] ?? 0;

if (!$tipo || !$id) {
    header("Location: index.php");
    exit;
}

// Obtener datos del elemento
$table = ($tipo === 'circuito') ? 'circuitos' : (($tipo === 'piloto') ? 'pilotos' : 'vehiculos');
$stmt = $pdo->prepare("SELECT nombre, imagen_url FROM $table WHERE id = ?");
$stmt->execute([$id]);
$elemento = $stmt->fetch();

if (!$elemento) die("No encontrado.");

// Procesar nuevo mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $mensaje = trim($_POST['mensaje']);
    if (!empty($mensaje)) {
        $stmt = $pdo->prepare("INSERT INTO mensajes (id_usuario, tipo_elemento, id_elemento, mensaje) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $tipo, $id, $mensaje]);
    }
}

// Obtener mensajes
$stmt = $pdo->prepare("SELECT m.*, u.usuario, u.foto_perfil FROM mensajes m JOIN usuarios u ON m.id_usuario = u.id WHERE m.tipo_elemento = ? AND m.id_elemento = ? ORDER BY m.fecha ASC");
$stmt->execute([$tipo, $id]);
$mensajes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat - <?php echo $elemento['nombre']; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .chat-box { height: 400px; overflow-y: auto; background: rgba(0,0,0,0.3); border-radius: 20px; padding: 20px; border: 1px solid rgba(255,255,255,0.1); }
        .msg { display: flex; gap: 15px; margin-bottom: 20px; }
        .msg-avatar { width: 40px; height: 40px; border-radius: 50%; }
        .msg-content { background: rgba(255,255,255,0.05); padding: 10px 15px; border-radius: 15px; flex: 1; }
        .msg-user { font-weight: 700; color: var(--primary); font-size: 0.85rem; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"><span class="logo-red">Motor</span><span class="logo-white">sport</span></div>
            <ul class="nav-links"><li><a href="index.php">Inicio</a></li></ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 120px;">
        <div class="detail-container">
            <div style="width: 350px; min-width: 350px;">
                <img src="<?php echo $elemento['imagen_url']; ?>" style="width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <h2 style="margin-top: 20px; font-size: 2rem;"><?php echo $elemento['nombre']; ?></h2>
                <a href="detalle.php?tipo=<?php echo $tipo; ?>&id=<?php echo $id; ?>" class="back-link" style="display: block; margin-top: 10px;">← Ver Detalles Técnicos</a>
            </div>

            <div style="flex: 1;">
                <h2 class="section-title">Comunidad y Chat</h2>
                
                <div class="chat-box">
            <?php foreach ($mensajes as $m): ?>
                <div class="msg">
                    <img src="<?php echo $m['foto_perfil']; ?>" class="msg-avatar">
                    <div class="msg-content">
                        <div class="msg-user"><?php echo htmlspecialchars($m['usuario']); ?> <small style="color: #666;"><?php echo $m['fecha']; ?></small></div>
                        <p><?php echo nl2br(htmlspecialchars($m['mensaje'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="chat.php?tipo=<?php echo $tipo; ?>&id=<?php echo $id; ?>" method="POST" style="margin-top: 20px; display: flex; gap: 10px;">
                <input type="text" name="mensaje" placeholder="Escribe un mensaje..." required style="flex: 1; background: #222; border: 1px solid #444; color: white; padding: 10px; border-radius: 10px;">
                <button type="submit" class="submit-btn" style="margin-top: 0;">Enviar</button>
            </form>
                <?php else: ?>
                    <p style="margin-top: 20px;"><a href="login.php">Inicia sesión</a> para participar en el chat.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
