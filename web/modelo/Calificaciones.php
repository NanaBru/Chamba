<?php
require_once __DIR__ . '/config/conexion.php';

class Calificaciones {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // ENVIAR SOLICITUD DE RESEÑA
    public function enviarSolicitudResena($publicacion_id, $proveedor_id, $cliente_id) {
        $conn = $this->conexion->getConexion();
        
        // Verificar que no sea el mismo usuario
        if ($proveedor_id == $cliente_id) {
            return ['success' => false, 'mensaje' => 'No puedes enviarte solicitud a ti mismo'];
        }
        
        // Verificar si ya existe solicitud pendiente
        $check = $conn->prepare("SELECT id FROM solicitudes_resena 
                                 WHERE publicacion_id = ? AND cliente_id = ? AND estado = 'pendiente'");
        $check->bind_param("ii", $publicacion_id, $cliente_id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            return ['success' => false, 'mensaje' => 'Ya existe una solicitud pendiente'];
        }
        
        // Crear nueva solicitud
        $sql = "INSERT INTO solicitudes_resena (publicacion_id, proveedor_id, cliente_id) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $publicacion_id, $proveedor_id, $cliente_id);
        
        return $stmt->execute() ? 
            ['success' => true, 'mensaje' => 'Solicitud de reseña enviada'] : 
            ['success' => false, 'mensaje' => 'Error al enviar solicitud'];
    }

    // OBTENER SOLICITUDES PENDIENTES
    public function obtenerSolicitudesPendientes($usuario_id) {
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT sr.*, p.titulo, p.imagen, u.nombre, u.apellido
                FROM solicitudes_resena sr
                INNER JOIN publicaciones p ON p.id = sr.publicacion_id
                INNER JOIN usuario u ON u.id = sr.proveedor_id
                WHERE sr.cliente_id = ? AND sr.estado = 'pendiente'
                ORDER BY sr.fecha_solicitud DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // VERIFICAR SI PUEDE CALIFICAR
    public function puedeCalificar($publicacion_id, $usuario_id) {
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT id FROM solicitudes_resena 
                WHERE publicacion_id = ? AND cliente_id = ? AND estado = 'pendiente'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $publicacion_id, $usuario_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // GUARDAR CALIFICACIÓN
    public function guardarCalificacion($publicacion_id, $usuario_id, $estrellas, $comentario) {
        $conn = $this->conexion->getConexion();
        
        // Verificar que tenga solicitud pendiente
        if (!$this->puedeCalificar($publicacion_id, $usuario_id)) {
            return ['success' => false, 'mensaje' => 'No tienes permiso para calificar'];
        }
        
        // Insertar calificación
        $sql = "INSERT INTO calificasion (publicacion_id, usuario_id, estrellas, comentario) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $publicacion_id, $usuario_id, $estrellas, $comentario);
        
        if ($stmt->execute()) {
            // Marcar solicitud como completada
            $update = $conn->prepare("UPDATE solicitudes_resena SET estado = 'completada' 
                                      WHERE publicacion_id = ? AND cliente_id = ?");
            $update->bind_param("ii", $publicacion_id, $usuario_id);
            $update->execute();
            
            return ['success' => true, 'mensaje' => 'Reseña publicada correctamente'];
        }
        
        return ['success' => false, 'mensaje' => 'Error al guardar reseña'];
    }

    // OBTENER MIS PUBLICACIONES (para enviar solicitudes)
    public function obtenerMisPublicaciones($usuario_id) {
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT id, titulo, imagen FROM publicaciones WHERE usuario_id = ? ORDER BY titulo";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
