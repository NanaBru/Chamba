<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

require_once __DIR__ . '/../modelo/Mensajes.php';
require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Calificaciones.php';

$mensajesModel = new Mensajes();
$userModel = new Usuario();
$calificacionesModel = new Calificaciones();
$usuario_id = $_SESSION['usuario_id'];

// Contacto seleccionado
$contacto_id = isset($_GET['contacto']) ? (int)$_GET['contacto'] : 0;

// Enviar solicitud de reseña
if (isset($_POST['enviar_solicitud'])) {
    $pub_id = (int)$_POST['publicacion_id'];
    $resultado = $calificacionesModel->enviarSolicitudResena($pub_id, $usuario_id, $contacto_id);
    
    if ($resultado['success']) {
        // Enviar mensaje automático
        $mensajesModel->enviarMensaje($usuario_id, $contacto_id, 
            "📝 Te he enviado una solicitud para que califiques mi servicio. Busca el botón 'Ver solicitudes' en el chat.");
    }
    
    header("Location: /chamba/web/router.php?page=chat&contacto=$contacto_id");
    exit;
}

// Enviar mensaje normal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensaje'])) {
    $mensaje = trim($_POST['mensaje']);
    $receptor = (int)$_POST['receptor_id'];
    
    if (!empty($mensaje) && $receptor > 0) {
        $mensajesModel->enviarMensaje($usuario_id, $receptor, $mensaje);
        header("Location: /chamba/web/router.php?page=chat&contacto=$receptor");
        exit;
    }
}

// Obtener conversaciones
$conversaciones = $mensajesModel->obtenerConversaciones($usuario_id);

// Si hay contacto seleccionado
$mensajes = [];
$contacto = null;
$misPublicaciones = [];
$esProveedor = false;

if ($contacto_id > 0) {
    $mensajes = $mensajesModel->obtenerMensajes($usuario_id, $contacto_id);
    $contacto = $userModel->obtenerDatosUsuario($contacto_id);
    $mensajesModel->marcarComoLeidos($usuario_id, $contacto_id);
    
    // Obtener mis publicaciones si soy el que tiene servicios
    $misPublicaciones = $calificacionesModel->obtenerMisPublicaciones($usuario_id);
    $esProveedor = !empty($misPublicaciones);
}

// Obtener solicitudes pendientes del usuario actual
$solicitudesPendientes = $calificacionesModel->obtenerSolicitudesPendientes($usuario_id);

require_once __DIR__ . '/../vista/app/chat.php';
