<?php
header('Content-Type: application/json');

if (!isset($_GET['q']) || strlen($_GET['q']) < 2) {
    echo json_encode([]);
    exit;
}

require_once __DIR__ . '/../modelo/Publicaciones.php';

$pubModel = new Publicaciones();
$termino = trim($_GET['q']);
$sugerencias = $pubModel->obtenerSugerencias($termino);

echo json_encode($sugerencias);
