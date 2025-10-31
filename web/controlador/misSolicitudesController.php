<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

require_once __DIR__ . '/../modelo/Calificaciones.php';

$calificacionesModel = new Calificaciones();
$usuario_id = $_SESSION['usuario_id'];

// Procesar calificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calificar'])) {
    $pub_id = (int)$_POST['publicacion_id'];
    $estrellas = (int)$_POST['estrellas'];
    $comentario = trim($_POST['comentario']);
    
    if ($estrellas >= 1 && $estrellas <= 5) {
        $resultado = $calificacionesModel->guardarCalificacion($pub_id, $usuario_id, $estrellas, $comentario);
        
        if ($resultado['success']) {
            header("Location: /chamba/web/router.php?page=mis-solicitudes&success=1");
            exit;
        } else {
            $mensaje_error = $resultado['mensaje'];
        }
    } else {
        $mensaje_error = "Selecciona entre 1 y 5 estrellas";
    }
}

// Obtener solicitudes pendientes
$solicitudes = $calificacionesModel->obtenerSolicitudesPendientes($usuario_id);

require_once __DIR__ . '/../vista/app/misSolicitudes.php';
