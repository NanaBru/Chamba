<?php
// web/controlador/perfilController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si no hay sesión activa
if (empty($_SESSION['email'])) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

require_once __DIR__ . '/../modelo/Usuario.php';
$userModel = new Usuario();

// Obtener usuario actual con el método robusto
$email   = $_SESSION['email'];
$usuario = $userModel->getPerfilPorEmail($email);

// Variables para la vista
$fotoPath  = '';
$tieneFoto = false;
$inicial   = '';

// Subida de nueva foto de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $carpetaDestino = __DIR__ . "/../datos/usuarios/";
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $permitidas)) {
        header("Location: /chamba/web/router.php?page=perfil&error=" . urlencode('Formato de imagen no permitido'));
        exit;
    }

    $nombreArchivo = uniqid('usr_') . "." . $ext;
    $rutaDestino   = $carpetaDestino . $nombreArchivo;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
        $userModel->actualizarFotoPerfil($email, $nombreArchivo);
        $_SESSION['foto_perfil'] = $nombreArchivo; // opcional
    }

    header("Location: /chamba/web/router.php?page=perfil");
    exit;
}

// Preparar datos seguros para la vista
if ($usuario) {
    if (!empty($usuario['foto_perfil'])) {
        $fotoPath  = "/chamba/web/datos/usuarios/" . $usuario['foto_perfil'];
        $tieneFoto = file_exists(__DIR__ . "/../datos/usuarios/" . $usuario['foto_perfil']);
    }
    if (!empty($usuario['nombre'])) {
        $inicial = strtoupper(substr($usuario['nombre'], 0, 1));
    }
}

// Render de la vista (no tocar la vista)
require __DIR__ . '/../vista/app/usuario/perfil.php';
