<?php
// web/controlador/crearpubliControler.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    header("Location: /chamba/web/router.php?page=sesion&error=" . urlencode('Inicia sesión'));
    exit;
}

require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Publicaciones.php';

$userModel = new Usuario();
$pubModel  = new Publicaciones();

$mensaje = "";
$usuarioId = (int)$_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = $_POST['precio'] ?? 0;

    if ($titulo === '' || $descripcion === '' || !is_numeric($precio) || $precio < 0) {
        $mensaje = "Completa todos los campos válidos.";
    } else {
        $nombreImagen = null;
        
        if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $carpeta = __DIR__ . '/../datos/publicasiones/';
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg','jpeg','png','gif','webp'];
            
            if (!in_array($ext, $permitidas)) {
                $mensaje = "Formato de imagen no permitido.";
            } else {
                $nombreImagen = uniqid('pub_') . '.' . $ext;
                $destino = $carpeta . $nombreImagen;
                
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                    $mensaje = "No se pudo guardar la imagen.";
                    $nombreImagen = null;
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

require_once __DIR__ . '/../vista/app/crearpubli.php';
