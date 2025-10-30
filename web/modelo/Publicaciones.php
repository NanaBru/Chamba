<?php
// web/modelo/Publicaciones.php
require_once __DIR__ . '/config/conexion.php';

class Publicaciones {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // Todas las publicaciones con datos del autor
   // En web/modelo/Publicaciones.php
// En web/modelo/Publicaciones.php
public function getPublicaciones(): array {
    $conn = $this->conexion->getConexion();
    $sql = "SELECT p.id, p.titulo, p.descripcion, p.imagen, p.precio, p.fecha,
                   u.nombre, u.apellido,
                   ROUND(AVG(c.estrellas),1) AS rating
            FROM publicaciones p
            INNER JOIN usuario u ON u.id = p.usuario_id
            LEFT JOIN calificasion c ON c.publicacion_id = p.id
            GROUP BY p.id
            ORDER BY p.fecha DESC";
    $res = $conn->query($sql);

    $data = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}




    // Publicaciones por usuario (opcional)
    public function getPublicacionesPorUsuario(int $usuarioId): array {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT id, titulo, descripcion, imagen, precio, fecha
                FROM publicaciones
                WHERE usuario_id = ?
                ORDER BY fecha DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $res = $stmt->get_result();

        $data = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function crearPublicacion(int $usuarioId, string $titulo, string $descripcion, ?string $imagen, float $precio): bool {
    $conn = $this->conexion->getConexion();
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, descripcion, imagen, precio)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssd", $usuarioId, $titulo, $descripcion, $imagen, $precio);
    return $stmt->execute();
}

}
