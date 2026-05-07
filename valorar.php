<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$tipo = $_POST['tipo'];
$id_elemento = $_POST['id'];
$estrellas = $_POST['estrellas'];
$comentario = trim($_POST['comentario']);

// Guardar valoración
$stmt = $pdo->prepare("INSERT INTO valoraciones (id_usuario, tipo_elemento, id_elemento, estrellas, comentario) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $tipo, $id_elemento, $estrellas, $comentario]);

header("Location: detalle.php?tipo=$tipo&id=$id_elemento");
exit;
?>
