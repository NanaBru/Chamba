<?php
// controlador/publicacionControler.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../modelo/modeloPublicaciones.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("Error: publicación no encontrada.");
}

$pubModel = new ModeloPublicaciones();
$publicacion = $pubModel->getPublicacionPorId($id);

if (!$publicacion) {
    die("Error: esa publicación no existe.");
}
