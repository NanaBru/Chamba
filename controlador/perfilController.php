<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../sesion.php");
    exit;
}

require_once __DIR__ . "/../modelo/modeloPerfil.php";

$email = $_SESSION['email'];

// --- subir/cambiar foto ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $carpetaDestino = "../datos/";
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $nombreArchivo = uniqid()."_".basename($_FILES['foto_perfil']['name']);
    $rutaDestino   = $carpetaDestino.$nombreArchivo;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
        ModeloPerfil::actualizarFotoPerfil($email, $nombreArchivo);
    }
    header("Location: ../vista/usuario/perfil.php");
    exit;
}

// --- datos usuario ---
$usuario   = ModeloPerfil::obtenerUsuarioPorEmail($email);
$inicial   = strtoupper(substr($usuario['nombre'], 0, 1));
$foto      = $usuario['foto_perfil'] ?? '';
$fotoPath  = "../datos/".$foto;
$tieneFoto = !empty($foto) && file_exists($fotoPath);

require "../vista/usuario/perfil.php";
