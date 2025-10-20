<?php
require_once __DIR__ . '/../config/conexion.php';

class ModeloPublicaciones {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion();
    }

    // Todas las publicaciones
    public function getPublicaciones() {
        $sql = "
            SELECT p.id, p.titulo, p.descripcion, p.imagen, p.precio, p.fecha,
                   u.nombre, u.apellido
            FROM publicaciones p
            JOIN usuario u ON p.usuario_id = u.id
            ORDER BY p.fecha DESC
        ";
        return $this->conn->query($sql);
    }

    // Una publicación específica
    public function getPublicacionPorId($id) {
        $stmt = $this->conn->prepare("
            SELECT p.id, p.titulo, p.descripcion, p.imagen, p.precio, p.fecha,
                   u.nombre, u.apellido
            FROM publicaciones p
            JOIN usuario u ON p.usuario_id = u.id
            WHERE p.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
