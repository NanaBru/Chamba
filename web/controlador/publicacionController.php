<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../modelo/Publicaciones.php';
require_once __DIR__ . '/../modelo/Usuario.php';

$pubModel = new Publicaciones();
$userModel = new Usuario();

// Obtener ID de publicación
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: /chamba/web/router.php?page=inicio");
    exit;
}

// Obtener datos de la publicación
$publicacion = $pubModel->obtenerPublicacionPorId($id);

if (!$publicacion) {
    header("Location: /chamba/web/router.php?page=inicio");
    exit;
}

// Obtener datos del autor
$autor = $userModel->obtenerDatosUsuario($publicacion['usuario_id']);

// Obtener calificaciones con datos del usuario que calificó
$calificaciones = $pubModel->obtenerCalificaciones($id);

// Calcular promedio de estrellas
$promedioEstrellas = $pubModel->obtenerPromedioCalificacion($id);

require_once __DIR__ . '/../vista/app/verPublicacion.php';
