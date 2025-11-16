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
require_once __DIR__ . '/../modelo/Facturas.php';


$mensajesModel = new Mensajes();
$userModel = new Usuario();
$calificacionesModel = new Calificaciones();
$facturasModel = new Facturas();
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
            "📝 Te he enviado una solicitud para que califiques mi servicio. el voton se encuentra en la esquina superior derecha 'Ver solicitudes' en el chat.");
    }
    
    header("Location: /chamba/web/router.php?page=chat&contacto=$contacto_id");
    exit;
}

// Enviar factura
if (isset($_POST['enviar_factura'])) {
    $pub_id = isset($_POST['publicacion_id_factura']) ? (int)$_POST['publicacion_id_factura'] : null;
    $descripcion = trim($_POST['descripcion_factura']);
    $monto = (float)$_POST['monto_factura'];
    
    // Procesar fotos
    $fotos = [];
    if (isset($_FILES['fotos_factura']) && !empty($_FILES['fotos_factura']['name'][0])) {
        $carpetaDestino = __DIR__ . '/../datos/facturas/';
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        
        foreach ($_FILES['fotos_factura']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['fotos_factura']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['fotos_factura']['name'][$key], PATHINFO_EXTENSION));
                $permitidas = ['jpg','jpeg','png','gif','webp'];
                
                if (in_array($ext, $permitidas)) {
                    $nombreFoto = 'factura_' . uniqid() . '.' . $ext;
                    $rutaDestino = $carpetaDestino . $nombreFoto;
                    
                    if (move_uploaded_file($tmp_name, $rutaDestino)) {
                        $fotos[] = $nombreFoto;
                    }
                }
            }
        }
    }
    
    $resultado = $facturasModel->crearFactura($usuario_id, $contacto_id, $pub_id, $descripcion, $monto, $fotos);
    
    if ($resultado['success']) {
        $mensajesModel->enviarMensaje($usuario_id, $contacto_id, 
            "Te he enviado una factura por $" . number_format($monto, 2) . ". Revisa tus facturas pendientes.");
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
// Obtener facturas pendientes
$facturasPendientes = $facturasModel->obtenerFacturasPendientes($usuario_id);


require_once __DIR__ . '/../vista/app/chat.php';
