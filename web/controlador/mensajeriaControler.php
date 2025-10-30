<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../modelo/Mensaje.php';

$mensajeModel = new Mensaje();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/sesion.php");
    exit;
}

// Si llega un mensaje por POST → guardarlo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emisorId = $_SESSION['id_usuario'];
    $receptorId = $_POST['receptor_id'];
    $mensaje = trim($_POST['mensaje']);

    if (!empty($mensaje)) {
        $mensajeModel->enviarMensaje($emisorId, $receptorId, $mensaje);
    }

    exit; // no mostramos HTML
}

// Si se pide por GET → obtener historial
if (isset($_GET['receptor_id'])) {
    $emisorId = $_SESSION['id_usuario'];
    $receptorId = $_GET['receptor_id'];

    $mensajes = $mensajeModel->obtenerMensajes($emisorId, $receptorId);

    $data = [];
    while ($fila = $mensajes->fetch_assoc()) {
        $data[] = $fila;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
require __DIR__ . '/../vista/app/mensajeria.php';
?>