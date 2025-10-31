<?php
$adminModel = new Admin();

// Eliminar reseña
if (isset($_GET['eliminar_resena'])) {
    $id = (int)$_GET['eliminar_resena'];
    if ($adminModel->eliminarResena($id)) {
        header("Location: ?seccion=resenas&eliminado=1");
        exit;
    }
}

// Obtener todas las reseñas
$resenas = $adminModel->obtenerTodasResenas();

include __DIR__ . '/../vista/resenas.php';
