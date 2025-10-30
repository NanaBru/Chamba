<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../modelo/Publicaciones.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../vista/sesion.php");
    exit;
}

$pubModel = new Publicaciones();
$publicaciones = $pubModel->getPublicaciones();

include __DIR__ . '/../vista/app/publicaciones.php';
