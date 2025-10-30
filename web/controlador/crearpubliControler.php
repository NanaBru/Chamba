<?php
// web/controlador/crearpubliControler.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['email'])) {
    header("Location: /chamba/web/router.php?page=sesion&error=" . urlencode('Inicia sesión'));
    exit;
}

require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Publicaciones.php';

$userModel = new Usuario();
$pubModel  = new Publicaciones();

$mensaje = "";

// Obtener ID de usuario a partir del email en sesión
$usuario = $userModel->getUserByEmail($_SESSION['email']);
if (!$usuario) {
    header("Location: /chamba/web/router.php?page=sesion&error=" . urlencode('Sesión inválida'));
    exit;
}
$usuarioId = (int)$usuario['id'];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = $_POST['precio'] ?? 0;

    // Validaciones mínimas
    if ($titulo === '' || $descripcion === '' || !is_numeric($precio) || $precio < 0) {
        $mensaje = "Completa todos los campos válidos.";
    } else {
        // Manejo de imagen (opcional)
        $nombreImagen = null;
        if (!empty($_FILES['imagen']['name'])) {
            $carpeta = __DIR__ . '/../datos/publicasiones/';
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            $permitidas = ['jpg','jpeg','png','gif','webp'];
            if (!in_array($ext, $permitidas)) {
                $mensaje = "Formato de imagen no permitido.";
            } else {
                $nombreImagen = uniqid('pub_') . '.' . $ext;
                $destino = $carpeta . $nombreImagen;
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                    $mensaje = "No se pudo guardar la imagen.";
                }
            }
        }

        if ($mensaje === "") {
            $ok = $pubModel->crearPublicacion($usuarioId, $titulo, $descripcion, $nombreImagen, (float)$precio);
            if ($ok) {
                header("Location: /chamba/web/router.php?page=inicio");
                exit;
            } else {
                $mensaje = "No se pudo crear la publicación.";
            }
        }
    }
}

// Render de vista
require_once __DIR__ . '/../vista/app/crearpubli.php';
