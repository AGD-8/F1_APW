<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";
$error = "";

// Obtener datos actuales del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_usuario = trim($_POST['usuario']);
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    
    // Procesar cambio de nombre de usuario
    if (!empty($nuevo_usuario) && $nuevo_usuario !== $user['usuario']) {
        $stmt = $pdo->prepare("UPDATE usuarios SET usuario = ? WHERE id = ?");
        $stmt->execute([$nuevo_usuario, $user_id]);
        $_SESSION['username'] = $nuevo_usuario;
        $msg = "Perfil actualizado.";
    }

    // Procesar cambio de contraseña
    if (!empty($old_pass) && !empty($new_pass)) {
        if (password_verify($old_pass, $user['password'])) {
            $hashed_new = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_new, $user_id]);
            $msg = "Contraseña cambiada.";
        } else {
            $error = "La contraseña antigua es incorrecta.";
        }
    }

    // Procesar foto de perfil
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $ruta = "assets/user_" . $user_id . "." . $ext;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)) {
            $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
            $stmt->execute([$ruta, $user_id]);
            $msg = "Foto de perfil actualizada.";
        }
    }
    
    // Recargar datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Motorsport Hub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            margin-top: 120px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            padding: 40px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .avatar-preview {
            width: 100px; height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 2px solid var(--primary);
        }
        .form-group { margin-bottom: 20px; }
        .success { color: #00ff64; }
        .error { color: #ff4b38; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"><span class="logo-red">Motor</span><span class="logo-white">sport</span></div>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="profile-container">
        <h2>Gestionar Perfil</h2>
        <?php if ($msg): ?> <p class="success"><?php echo $msg; ?></p> <?php endif; ?>
        <?php if ($error): ?> <p class="error"><?php echo $error; ?></p> <?php endif; ?>
        
        <form action="perfil.php" method="POST" enctype="multipart/form-data">
            <div class="form-group" style="text-align: center;">
                <img src="<?php echo $user['foto_perfil']; ?>" class="avatar-preview" alt="Avatar">
                <br>
                <label>Cambiar foto</label>
                <input type="file" name="foto">
            </div>

            <div class="form-group">
                <label>Nombre de Usuario</label>
                <input type="text" name="usuario" value="<?php echo htmlspecialchars($user['usuario']); ?>" required>
            </div>

            <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 30px 0;">
            <h3>Cambiar Contraseña</h3>
            <div class="form-group">
                <label>Contraseña Actual</label>
                <input type="password" name="old_password">
            </div>
            <div class="form-group">
                <label>Nueva Contraseña</label>
                <input type="password" name="new_password">
            </div>

            <button type="submit" class="submit-btn" style="width: 100%;">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
