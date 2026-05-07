<?php
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    die("Acceso denegado. Debes iniciar sesión.");
}

if (isset($_GET['tipo']) && isset($_GET['id'])) {
    $tipo = $_GET['tipo'];
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Verificar si el usuario es el creador o si quieres permitir a todos (en este caso permitiremos a todos por simplicidad, o al creador)
    // Para esta demo permitiremos que cualquier usuario registrado borre para facilitar la gestión.
    
    $table = "";
    if ($tipo === 'circuito') $table = "circuitos";
    elseif ($tipo === 'piloto') $table = "pilotos";
    elseif ($tipo === 'vehiculo') $table = "vehiculos";

    if ($table !== "") {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        
        // También borrar valoraciones y mensajes relacionados si se desea (cascada)
        // La base de datos ya tiene ON DELETE CASCADE en las claves foráneas.
    }
}

header("Location: index.php");
exit;
?>
