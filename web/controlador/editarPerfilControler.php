<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Publicaciones.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

$modelo = new Usuario();
$pubModelo = new Publicaciones();
$usuario_id = $_SESSION['usuario_id'];
$mensaje_error = "";
$mensaje_exito = "";

// Obtener datos actuales del usuario
$usuario = $modelo->obtenerDatosUsuario($usuario_id);

if (!$usuario) {
    $mensaje_error = "No se pudieron cargar los datos del usuario.";
}

// Obtener publicaciones del usuario
$misPublicaciones = $pubModelo->getPublicacionesPorUsuario($usuario_id);

// Procesar eliminación de publicación
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $pub_id = (int)$_GET['eliminar'];
    $resultado = $pubModelo->eliminarPublicacion($pub_id, $usuario_id);
    
    if ($resultado) {
        header("Location: /chamba/web/router.php?page=editar-perfil&eliminada=1");
        exit;
    } else {
        $mensaje_error = "No se pudo eliminar la publicación.";
    }
}

// Procesar formulario de edición de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['eliminar'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $edad = intval($_POST['edad'] ?? 0);
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $descripcion = substr(strip_tags(trim($_POST['descripcion'] ?? '')), 0, 600);
    $password = trim($_POST['password'] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || $edad < 18 || empty($telefono) || empty($email)) {
        $mensaje_error = "Completa todos los campos obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje_error = "Email no válido.";
    } elseif (strlen($telefono) !== 9 || !ctype_digit($telefono)) {
        $mensaje_error = "Teléfono debe tener 9 dígitos.";
    } else {
        $resultado = $modelo->actualizarPerfilCompleto($usuario_id, $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password);
        
        if ($resultado['success']) {
            $_SESSION['email'] = $email;
            header("Location: /chamba/web/router.php?page=perfil");
            exit;
        } else {
            $mensaje_error = $resultado['mensaje'];
        }
    }
}

// Mensaje de éxito si se eliminó una publicación
if (isset($_GET['eliminada'])) {
    $mensaje_exito = "Publicación eliminada correctamente.";
}

require_once __DIR__ . '/../vista/app/usuario/editarPerfil.php';
