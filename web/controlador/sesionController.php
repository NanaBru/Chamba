<?php
require_once __DIR__ . '/../modelo/Usuario.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioModel = new Usuario();
$mensaje_error = '';
$mensaje_exito = $_GET['mensaje'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    $usuario = $usuarioModel->iniciarSesion($email, $password);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre']     = $usuario['nombre'];
        $_SESSION['email']      = $usuario['email'];
        $_SESSION['rol']        = $usuario['rol'] ?? 'usuario';
        header('Location: /chamba/web/router.php?page=inicio');
        exit;
    } else {
        $mensaje_error = 'Credenciales incorrectas';
    }
}

// Cargar la vista
require_once __DIR__ . '/../vista/sesion.php';
