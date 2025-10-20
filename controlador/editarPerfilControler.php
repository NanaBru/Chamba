<?php
session_start();
require_once __DIR__ . '/../modelo/modeloEditarperfil.php';

// Redirigir si no hay sesión
if (!isset($_SESSION['email'])) {
    header("Location: ../../vista/sesion.php");
    exit;
}

$modelo = new ModeloEditarPerfil();
$email_sesion = $_SESSION['email'];

// Obtener usuario actual
$usuario = $modelo->getUsuarioPorEmail($email_sesion);
if (!$usuario) {
    $_SESSION['error_mensaje'] = "Usuario no encontrado";
    header("Location: ../../vista/usuario/perfil.php");
    exit;
}

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Limpiar datos
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $edad     = intval($_POST['edad'] ?? 0);
    $telefono = trim($_POST['telefono'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validaciones
    $errores = [];
    if (empty($nombre)) $errores[] = "El nombre es obligatorio";
    if (empty($apellido)) $errores[] = "El apellido es obligatorio";
    if ($edad < 18 || $edad > 99) $errores[] = "Edad inválida";
    if (empty($telefono)) $errores[] = "Teléfono obligatorio";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Email inválido";

    // Verificar email duplicado
    if ($email !== $email_sesion && $modelo->emailExiste($email, $usuario['id'])) {
        $errores[] = "El email ya está en uso por otro usuario";
    }

    if (!empty($errores)) {
        $_SESSION['error_mensaje'] = implode(", ", $errores);
        header("Location: ../../vista/usuario/editarPerfil.php");
        exit;
    }

    // Actualizar perfil
    $password_hash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
    $exito = $modelo->actualizarPerfil($usuario['id'], $nombre, $apellido, $edad, $telefono, $email, $password_hash);

    // Actualizar sesión si cambió email
    if ($exito && $email !== $email_sesion) {
        $_SESSION['email'] = $email;
    }

    $_SESSION['success_mensaje'] = $exito ? "Perfil actualizado correctamente" : "No se realizaron cambios";
    header("Location: ../vista/usuario/perfil.php");
    exit;
}
