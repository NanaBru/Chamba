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
                   COALESCE(ROUND(AVG(c.estrellas),1), 5.0) AS rating
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

    $sql = "SELECT p.id, p.titulo, p.descripcion, p.imagen, p.precio, p.fecha,
                   COALESCE(ROUND(AVG(c.estrellas),1), 5.0) AS rating
            FROM publicaciones p
            LEFT JOIN calificasion c ON c.publicacion_id = p.id
            WHERE p.usuario_id = ?
            GROUP BY p.id
            ORDER BY p.fecha DESC";

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




// OBTENER PUBLICACIÓN POR ID CON DATOS DEL AUTOR
public function obtenerPublicacionPorId($id) {
    $conn = $this->conexion->getConexion();
    $sql = "SELECT p.*, u.nombre, u.apellido, u.foto_perfil
            FROM publicaciones p
            INNER JOIN usuario u ON p.usuario_id = u.id
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}

// OBTENER TODAS LAS CALIFICACIONES DE UNA PUBLICACIÓN
public function obtenerCalificaciones($publicacion_id) {
    $conn = $this->conexion->getConexion();
    $sql = "SELECT c.*, u.nombre, u.apellido, u.foto_perfil
            FROM calificasion c
            INNER JOIN usuario u ON c.usuario_id = u.id
            WHERE c.publicacion_id = ?
            ORDER BY c.fecha DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $publicacion_id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

// OBTENER PROMEDIO DE CALIFICACIÓN
public function obtenerPromedioCalificacion($publicacion_id) {
    $conn = $this->conexion->getConexion();
    $sql = "SELECT COALESCE(ROUND(AVG(estrellas), 1), 0) AS promedio,
                   COUNT(*) AS total_calificaciones
            FROM calificasion
            WHERE publicacion_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $publicacion_id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}

// ELIMINAR PUBLICACIÓN (solo si pertenece al usuario)
public function eliminarPublicacion($publicacion_id, $usuario_id) {
    try {
        $conn = $this->conexion->getConexion();
        
        // Verificar que la publicación pertenezca al usuario
        $check = $conn->prepare("SELECT imagen FROM publicaciones WHERE id = ? AND usuario_id = ?");
        $check->bind_param("ii", $publicacion_id, $usuario_id);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows === 0) {
            return false; // No es del usuario
        }
        
        $pub = $result->fetch_assoc();
        
        // Eliminar archivo de imagen si existe
        if (!empty($pub['imagen'])) {
            $rutaImagen = __DIR__ . '/../datos/publicasiones/' . $pub['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
        
        // Eliminar de la base de datos
        $stmt = $conn->prepare("DELETE FROM publicaciones WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $publicacion_id, $usuario_id);
        return $stmt->execute();
        
    } catch (Exception $e) {
        return false;
    }
}





}
