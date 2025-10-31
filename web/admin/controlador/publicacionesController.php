<?php
$adminModel = new Admin();

// Eliminar publicación
if (isset($_GET['eliminar_pub'])) {
    $id = (int)$_GET['eliminar_pub'];
    if ($adminModel->eliminarPublicacion($id)) {
        header("Location: ?seccion=publicaciones&eliminado=1");
        exit;
    }
}

// Obtener todas las publicaciones
$publicaciones = $adminModel->obtenerTodasPublicaciones();

include __DIR__ . '/../vista/publicaciones.php';
