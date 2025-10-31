<?php
require_once __DIR__ . '/config/conexion.php';

class Mensajes {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // OBTENER CONVERSACIONES (lista de contactos con último mensaje)
    public function obtenerConversaciones($usuario_id) {
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT 
                    CASE 
                        WHEN m.emisor_id = ? THEN m.receptor_id
                        ELSE m.emisor_id
                    END AS contacto_id,
                    u.nombre, u.apellido, u.foto_perfil,
                    m.mensaje AS ultimo_mensaje,
                    m.fecha_envio AS ultima_fecha,
                    (SELECT COUNT(*) FROM mensajes 
                     WHERE emisor_id = contacto_id 
                     AND receptor_id = ? 
                     AND leido = 0) AS no_leidos
                FROM mensajes m
                INNER JOIN usuario u ON u.id = CASE 
                    WHEN m.emisor_id = ? THEN m.receptor_id 
                    ELSE m.emisor_id 
                END
                WHERE m.emisor_id = ? OR m.receptor_id = ?
                GROUP BY contacto_id
                ORDER BY m.fecha_envio DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiii", $usuario_id, $usuario_id, $usuario_id, $usuario_id, $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // OBTENER MENSAJES DE UNA CONVERSACIÓN
    public function obtenerMensajes($usuario_id, $contacto_id) {
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT m.*, u.nombre, u.apellido, u.foto_perfil
                FROM mensajes m
                INNER JOIN usuario u ON u.id = m.emisor_id
                WHERE (m.emisor_id = ? AND m.receptor_id = ?)
                   OR (m.emisor_id = ? AND m.receptor_id = ?)
                ORDER BY m.fecha_envio ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $usuario_id, $contacto_id, $contacto_id, $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // ENVIAR MENSAJE
    public function enviarMensaje($emisor_id, $receptor_id, $mensaje) {
        $conn = $this->conexion->getConexion();
        
        $sql = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $emisor_id, $receptor_id, $mensaje);
        return $stmt->execute();
    }

    // MARCAR MENSAJES COMO LEÍDOS
    public function marcarComoLeidos($usuario_id, $contacto_id) {
        $conn = $this->conexion->getConexion();
        
        $sql = "UPDATE mensajes SET leido = 1 
                WHERE emisor_id = ? AND receptor_id = ? AND leido = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $contacto_id, $usuario_id);
        return $stmt->execute();
    }

    // INICIAR CONVERSACIÓN (verificar si existe)
    public function iniciarConversacion($usuario_id, $contacto_id) {
        $conn = $this->conexion->getConexion();
        
        // Verificar si ya existe conversación
        $sql = "SELECT COUNT(*) as total FROM mensajes 
                WHERE (emisor_id = ? AND receptor_id = ?) 
                   OR (emisor_id = ? AND receptor_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $usuario_id, $contacto_id, $contacto_id, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['total'] > 0;
    }

    // ENVIAR SOLICITUD DE RESEÑA
public function enviarSolicitudResena($publicacion_id, $proveedor_id, $cliente_id) {
    $conn = $this->conexion->getConexion();
    
    // Verificar si ya existe una solicitud pendiente
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
    
    if ($stmt->execute()) {
        // Enviar mensaje automático
        $mensaje = "📝 Te he enviado una solicitud para calificar el servicio: " . $this->obtenerTituloPublicacion($publicacion_id);
        $this->enviarMensaje($proveedor_id, $cliente_id, $mensaje);
        return ['success' => true, 'mensaje' => 'Solicitud enviada'];
    }
    
    return ['success' => false, 'mensaje' => 'Error al enviar solicitud'];
}

// OBTENER SOLICITUDES PENDIENTES DEL USUARIO
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

// MARCAR SOLICITUD COMO COMPLETADA
public function completarSolicitud($solicitud_id) {
    $conn = $this->conexion->getConexion();
    
    $sql = "UPDATE solicitudes_resena SET estado = 'completada' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $solicitud_id);
    return $stmt->execute();
}

// OBTENER TITULO DE PUBLICACION
private function obtenerTituloPublicacion($publicacion_id) {
    $conn = $this->conexion->getConexion();
    
    $sql = "SELECT titulo FROM publicaciones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $publicacion_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['titulo'] ?? 'un servicio';
}


}