<?php
$adminModel = new Admin();

// Crear categoría
if (isset($_POST['crear_categoria'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $icono = trim($_POST['icono']);
    
    if ($adminModel->crearCategoria($nombre, $descripcion, $icono)) {
        header("Location: ?seccion=categorias&creada=1");
        exit;
    } else {
        $mensaje_error = "Error al crear categoría";
    }
}

// Eliminar categoría
if (isset($_GET['eliminar_cat'])) {
    $id = (int)$_GET['eliminar_cat'];
    if ($adminModel->eliminarCategoria($id)) {
        header("Location: ?seccion=categorias&eliminada=1");
        exit;
    }
}

// Obtener todas las categorías
$categorias = $adminModel->obtenerTodasCategorias();

include __DIR__ . '/../vista/categorias.php';
