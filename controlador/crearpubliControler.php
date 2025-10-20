<?php
session_start();
require_once __DIR__ . '/../modelo/modeloCrearpubli.php';
require_once __DIR__ . '/../config/conexion.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../../vista/sesion.php");
    exit;
}

// Obtener datos del usuario logueado
$conn = Conexion::getConexion();
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id, nombre, apellido FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$usuario = $res->fetch_assoc();
$stmt->close();
$conn->close();

if (!$usuario) die("Usuario no encontrado.");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = trim($_POST['precio'] ?? '');
    $imagenRuta  = null;

    // Validar precio
    if ($precio === '' || !is_numeric($precio) || $precio < 0) {
        $mensaje = "Ingrese un precio válido.";
    }

    // Procesar imagen
    if (empty($mensaje) && !empty($_FILES['imagen']['name'])) {
        $carpeta = "../../uploads/";
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

        $nombreArchivo = time() . "_" . basename($_FILES['imagen']['name']);
        $destino = $carpeta . $nombreArchivo;

        $tipo = mime_content_type($_FILES['imagen']['tmp_name']);
        if (preg_match('/image\/(jpeg|png|gif)/', $tipo)) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $imagenRuta = "uploads/" . $nombreArchivo;
            } else {
                $mensaje = "Error al subir la imagen.";
            }
        } else {
            $mensaje = "Formato de imagen no válido (solo jpg, png, gif).";
        }
    }

    if ($titulo && $descripcion && empty($mensaje)) {
        if (modeloPublicaciones::crearPublicacion($usuario['id'], $titulo, $descripcion, $imagenRuta, $precio)) {
            header("Location: inicio.php");
            exit;
        } else {
            $mensaje = "Error al guardar la publicación.";
        }
    } elseif (!$titulo || !$descripcion) {
        $mensaje = "Completa título y descripción.";
    }
}
