<?php
require_once 'config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($usuario) || empty($pass)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($pass !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        if ($stmt->fetch()) {
            $error = "El nombre de usuario ya está en uso.";
        } else {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
            if ($stmt->execute([$usuario, $hashed_pass])) {
                header("Location: login.php?msg=registered");
                exit;
            } else {
                $error = "Error al crear la cuenta.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Motorsport Hub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-container {
            margin-top: 150px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            padding: 40px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .auth-container h2 { margin-bottom: 20px; text-align: center; }
        .input-group { margin-bottom: 15px; }
        .error { color: #ff4b38; margin-bottom: 10px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Crear Cuenta</h2>
        <?php if ($error): ?> <p class="error"><?php echo $error; ?></p> <?php endif; ?>
        <form action="registro.php" method="POST">
            <div class="input-group">
                <label>Usuario</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <label>Confirmar Contraseña</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="submit-btn" style="width: 100%;">Registrarse</button>
        </form>
        <p style="margin-top: 20px; text-align: center;">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>
