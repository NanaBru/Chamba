<?php
// controlador/inicioControler.php

// arrancar sesión sólo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// incluir modelos (ellos llaman a Conexion::getConexion() en su __construct)
require_once __DIR__ . '/../modelo/modeloUsuario.php';
require_once __DIR__ . '/../modelo/modeloPublicaciones.php';

// Evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Validar sesión (si la vista incluye este controlador, la redirección funciona)
if (!isset($_SESSION['email'])) {
    header("Location: ../sesion.php");
    exit;
}

// Instanciar modelos — ellos internamente crean la conexión
$userModel = new ModeloUsuario();
$pubModel  = new ModeloPublicaciones();

// Obtener datos
$usuario = $userModel->getUserByEmail($_SESSION['email']); // array asociativo o null

$rawPublicaciones = $pubModel->getPublicaciones();

$publicaciones = [];

if ($rawPublicaciones instanceof mysqli_result) {
    while ($row = $rawPublicaciones->fetch_assoc()) {
        $publicaciones[] = $row;
    }
} elseif (is_array($rawPublicaciones)) {
    $publicaciones = $rawPublicaciones;
} elseif ($rawPublicaciones) {

    foreach ($rawPublicaciones as $r) $publicaciones[] = (array) $r;
}
