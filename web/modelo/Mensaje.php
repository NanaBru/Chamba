<?php
require_once __DIR__ . '/config/conexion.php';

class Mensaje extends Conexion {

    public function enviarMensaje($emisorId, $receptorId, $mensaje) {
        $conexion = $this->getConexion();
        $stmt = $conexion->prepare("INSERT INTO mensajes (emisor_id, receptor_id, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $emisorId, $receptorId, $mensaje);
        return $stmt->execute();
    }

    public function obtenerMensajes($emisorId, $receptorId) {
        $conexion = $this->getConexion();
        $stmt = $conexion->prepare("
            SELECT m.*, 
                   u1.nombre AS nombre_emisor,
                   u2.nombre AS nombre_receptor
            FROM mensajes m
            JOIN usuario u1 ON m.emisor_id = u1.id
            JOIN usuario u2 ON m.receptor_id = u2.id
            WHERE (m.emisor_id = ? AND m.receptor_id = ?)
               OR (m.emisor_id = ? AND m.receptor_id = ?)
            ORDER BY m.fecha_envio ASC
        ");
        $stmt->bind_param("iiii", $emisorId, $receptorId, $receptorId, $emisorId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
