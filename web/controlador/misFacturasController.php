<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

require_once __DIR__ . '/../modelo/Facturas.php';
require_once __DIR__ . '/../modelo/Mensajes.php';

$facturasModel = new Facturas();
$mensajesModel = new Mensajes();
$usuario_id = $_SESSION['usuario_id'];

// Marcar como pagada
if (isset($_POST['pagar_factura'])) {
    $factura_id = (int)$_POST['factura_id'];
    $factura = $facturasModel->obtenerFactura($factura_id);
    
    if ($factura && $factura['cliente_id'] == $usuario_id) {
        if ($facturasModel->marcarComoPagada($factura_id)) {
            // Notificar al proveedor
            $mensajesModel->enviarMensaje($usuario_id, $factura['proveedor_id'], 
                "✅ He confirmado el pago de la factura por $" . number_format($factura['monto'], 2));
        }
    }
    
    header("Location: /chamba/web/router.php?page=mis-facturas");
    exit;
}

// Rechazar factura
if (isset($_POST['rechazar_factura'])) {
    $factura_id = (int)$_POST['factura_id'];
    $factura = $facturasModel->obtenerFactura($factura_id);
    
    if ($factura && $factura['cliente_id'] == $usuario_id) {
        if ($facturasModel->rechazarFactura($factura_id)) {
            // Notificar al proveedor
            $mensajesModel->enviarMensaje($usuario_id, $factura['proveedor_id'], 
                "❌ He rechazado la factura por $" . number_format($factura['monto'], 2));
        }
    }
    
    header("Location: /chamba/web/router.php?page=mis-facturas");
    exit;
}

// Obtener facturas pendientes
$facturasPendientes = $facturasModel->obtenerFacturasPendientes($usuario_id);

require_once __DIR__ . '/../vista/app/misFacturas.php';
