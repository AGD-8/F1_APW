<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['usuario'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Motorsport Hub</title>
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
        .success { color: #00ff64; margin-bottom: 10px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
            <p class="success">Registro completado. Ya puedes iniciar sesión.</p>
        <?php endif; ?>
        <?php if ($error): ?> <p class="error"><?php echo $error; ?></p> <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label>Usuario</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn" style="width: 100%;">Entrar</button>
        </form>
        <p style="margin-top: 20px; text-align: center;">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>
</body>
</html>
