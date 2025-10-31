<?php
$adminModel = new Admin();

// Eliminar mensaje
if (isset($_GET['eliminar_msg'])) {
    $id = (int)$_GET['eliminar_msg'];
    if ($adminModel->eliminarMensaje($id)) {
        header("Location: ?seccion=mensajes&eliminado=1");
        exit;
    }
}

// Obtener todos los mensajes
$mensajes = $adminModel->obtenerTodosMensajes();

include __DIR__ . '/../vista/mensajes.php';
