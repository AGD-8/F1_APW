<?php
// functions.php
require_once 'config.php';

function getAverageRating($pdo, $tipo, $id) {
    $stmt = $pdo->prepare("SELECT AVG(estrellas) as avg FROM valoraciones WHERE tipo_elemento = ? AND id_elemento = ?");
    $stmt->execute([$tipo, $id]);
    $res = $stmt->fetch();
    return $res['avg'] ? round($res['avg'], 1) : 0;
}

function renderStars($avg) {
    $html = '<div class="star-rating">';
    for ($i = 1; $i <= 5; $i++) {
        $class = ($i <= round($avg)) ? 'star filled' : 'star';
        $html .= '<span class="' . $class . '">★</span>';
    }
    $html .= '<span class="avg-rating">(' . $avg . ')</span>';
    $html .= '</div>';
    return $html;
}
?>
