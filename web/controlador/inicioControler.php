<?php
// web/controlador/inicioControler.php

// Sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Requiere login
if (empty($_SESSION['email'])) {
    header("Location: /chamba/web/router.php?page=sesion&error=" . urlencode('Inicia sesión'));
    exit;
}

// Modelos
require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Publicaciones.php';

// No cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Instancias
$userModel = new Usuario();
$pubModel  = new Publicaciones();

// Usuario (si falla, no rompas flujo)
$usuario = null;
if (method_exists($userModel, 'getUserByEmail')) {
    $usuario = $userModel->getUserByEmail($_SESSION['email']);
}

// Publicaciones (array)
$publicaciones = $pubModel->getPublicaciones();

// Render
include __DIR__ . '/../vista/app/inicio.php';
