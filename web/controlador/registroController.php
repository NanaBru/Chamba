<?php
require_once __DIR__ . '/../modelo/Usuario.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioModel = new Usuario();
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula   = trim($_POST['cedula']   ?? ''); // Nuevo campo
    $nombre   = trim($_POST['nombre']   ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $edad     = (int)($_POST['edad']    ?? 0);
    $telefono = trim($_POST['telefono'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    if ($password !== $password_confirm) {
        $mensaje_error = "Las contraseñas no coinciden.";
    } elseif (empty($nombre) || empty($apellido) || $edad < 18 || empty($email) || empty($password)) {
        $mensaje_error = "Todos los campos son obligatorios y debes ser mayor de 18 años.";
    } else {
        // Pasar cédula al modelo
        $resultado = $usuarioModel->registrarUsuario($nombre, $apellido, $edad, $telefono, $email, $password, null, $cedula);

        if ($resultado['success']) {
            header('Location: /chamba/web/router.php?page=sesion&mensaje=' . urlencode('Usuario registrado, inicie sesión'));
            exit;
        } else {
            $mensaje_error = $resultado['mensaje'];
        }
    }
}

// Cargar la vista
require_once __DIR__ . '/../vista/registro.php';
