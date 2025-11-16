<?php
require_once __DIR__ . '/config/conexion.php';

class Facturas {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // Crear factura
    public function crearFactura($proveedor_id, $cliente_id, $publicacion_id, $descripcion, $monto, $fotos = []) {
        try {
            $conn = $this->conexion->getConexion();
            
            // Convertir array de fotos a JSON
            $fotos_json = !empty($fotos) ? json_encode($fotos) : null;
            
            $sql = "INSERT INTO facturas (proveedor_id, cliente_id, publicacion_id, descripcion, monto, fotos) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiisds", $proveedor_id, $cliente_id, $publicacion_id, $descripcion, $monto, $fotos_json);
            
            if ($stmt->execute()) {
                return ['success' => true, 'factura_id' => $stmt->insert_id];
            }
            return ['success' => false, 'mensaje' => 'Error al crear factura'];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => $e->getMessage()];
        }
    }

    // Obtener facturas pendientes del cliente
    public function obtenerFacturasPendientes($cliente_id) {
        try {
            $conn = $this->conexion->getConexion();
            
            $sql = "SELECT f.*, p.titulo as servicio, u.nombre, u.apellido 
                    FROM facturas f
                    LEFT JOIN publicaciones p ON p.id = f.publicacion_id
                    INNER JOIN usuario u ON u.id = f.proveedor_id
                    WHERE f.cliente_id = ? AND f.estado = 'pendiente'
                    ORDER BY f.fecha_creacion DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $cliente_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Obtener una factura específica
    public function obtenerFactura($factura_id) {
        try {
            $conn = $this->conexion->getConexion();
            
            $sql = "SELECT f.*, p.titulo as servicio, 
                    prov.nombre as proveedor_nombre, prov.apellido as proveedor_apellido,
                    cli.nombre as cliente_nombre, cli.apellido as cliente_apellido
                    FROM facturas f
                    LEFT JOIN publicaciones p ON p.id = f.publicacion_id
                    INNER JOIN usuario prov ON prov.id = f.proveedor_id
                    INNER JOIN usuario cli ON cli.id = f.cliente_id
                    WHERE f.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $factura_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            return null;
        }
    }

    // Marcar factura como pagada
    public function marcarComoPagada($factura_id) {
        try {
            $conn = $this->conexion->getConexion();
            
            $sql = "UPDATE facturas SET estado = 'pagada', fecha_pago = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $factura_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Rechazar factura
    public function rechazarFactura($factura_id) {
        try {
            $conn = $this->conexion->getConexion();
            
            $sql = "UPDATE facturas SET estado = 'rechazada' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $factura_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
