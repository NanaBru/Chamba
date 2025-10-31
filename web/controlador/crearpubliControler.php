<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

require_once __DIR__ . '/../modelo/Publicaciones.php';
require_once __DIR__ . '/../modelo/Admin.php';

$pubModel = new Publicaciones();
$adminModel = new Admin();

// Obtener categorías para el select
$categorias = $adminModel->obtenerTodasCategorias();

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $categoria_id = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null;
    
    // Procesar imagen (tu código existente)
    $nombreImagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Tu código de subida de imagen
        $nombreImagen = time() . '_' . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../datos/publicasiones/' . $nombreImagen);
    }
    
    // Guardar publicación con categoría
    $resultado = $pubModel->crearPublicacion($usuario_id, $titulo, $descripcion, $precio, $nombreImagen, $categoria_id);
    
    if ($resultado) {
        $mensaje = "Publicación creada con éxito";
    } else {
        $mensaje = "Error al crear la publicación";
    }
}

require_once __DIR__ . '/../vista/app/crearpubli.php';
