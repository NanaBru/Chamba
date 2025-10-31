<?php
/* require_once __DIR__ . '/config/conexion.php';

class Publicacion {
    private $conn;

    public function __construct() {
        $this->conn = (new Conexion())->getConexion();
    }

    // 🟩 Obtener publicaciones de un usuario
    public function obtenerPorUsuario($usuario_id) {
        $sql = "SELECT * FROM publicaciones WHERE usuario_id = ? ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
 */