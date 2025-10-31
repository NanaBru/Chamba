<?php
require_once __DIR__ . '/config/conexion.php';

class Admin {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // ========== USUARIOS ==========
    public function obtenerTodosUsuarios() {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT id, nombre, apellido, edad, telefono, email, rol, foto_perfil, fecha_creacion 
                FROM usuario ORDER BY fecha_creacion DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarUsuario($id) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ? AND rol != 'administrador'");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function crearUsuario($datos) {
        $conn = $this->conexion->getConexion();
        $passHash = password_hash($datos['password'], PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO usuario (nombre, apellido, edad, telefono, email, password, rol) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissss", 
            $datos['nombre'], $datos['apellido'], $datos['edad'], 
            $datos['telefono'], $datos['email'], $passHash, $datos['rol']
        );
        return $stmt->execute();
    }

    public function actualizarUsuario($id, $datos) {
        $conn = $this->conexion->getConexion();
        
        if (!empty($datos['password'])) {
            $passHash = password_hash($datos['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, password=?, rol=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssi", 
                $datos['nombre'], $datos['apellido'], $datos['edad'], 
                $datos['telefono'], $datos['email'], $passHash, $datos['rol'], $id
            );
        } else {
            $sql = "UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, rol=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisssi", 
                $datos['nombre'], $datos['apellido'], $datos['edad'], 
                $datos['telefono'], $datos['email'], $datos['rol'], $id
            );
        }
        return $stmt->execute();
    }

    // ========== PUBLICACIONES ==========
    public function obtenerTodasPublicaciones() {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT p.*, u.nombre, u.apellido 
                FROM publicaciones p 
                INNER JOIN usuario u ON p.usuario_id = u.id 
                ORDER BY p.fecha DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarPublicacion($id) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("DELETE FROM publicaciones WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ========== MENSAJES ==========
    public function obtenerTodosMensajes() {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT m.*, 
                       e.nombre as emisor_nombre, e.apellido as emisor_apellido,
                       r.nombre as receptor_nombre, r.apellido as receptor_apellido
                FROM mensajes m
                INNER JOIN usuario e ON m.emisor_id = e.id
                INNER JOIN usuario r ON m.receptor_id = r.id
                ORDER BY m.fecha_envio DESC LIMIT 500";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarMensaje($id) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("DELETE FROM mensajes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ========== RESEÑAS ==========
    public function obtenerTodasResenas() {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT c.*, 
                       u.nombre, u.apellido,
                       p.titulo as publicacion_titulo
                FROM calificasion c
                INNER JOIN usuario u ON c.usuario_id = u.id
                INNER JOIN publicaciones p ON c.publicacion_id = p.id
                ORDER BY c.fecha DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarResena($id) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("DELETE FROM calificasion WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ========== CATEGORÍAS ==========
    public function obtenerTodasCategorias() {
        $conn = $this->conexion->getConexion();
        $result = $conn->query("SELECT * FROM categorias ORDER BY nombre");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function crearCategoria($nombre, $descripcion, $icono) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("INSERT INTO categorias (nombre, descripcion, icono) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $descripcion, $icono);
        return $stmt->execute();
    }

    public function eliminarCategoria($id) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ========== ESTADÍSTICAS ==========
    public function obtenerEstadisticas() {
        $conn = $this->conexion->getConexion();
        
        $stats = [];
        $stats['total_usuarios'] = $conn->query("SELECT COUNT(*) as total FROM usuario WHERE rol='usuario'")->fetch_assoc()['total'];
        $stats['total_publicaciones'] = $conn->query("SELECT COUNT(*) as total FROM publicaciones")->fetch_assoc()['total'];
        $stats['total_mensajes'] = $conn->query("SELECT COUNT(*) as total FROM mensajes")->fetch_assoc()['total'];
        $stats['total_resenas'] = $conn->query("SELECT COUNT(*) as total FROM calificasion")->fetch_assoc()['total'];
        
        return $stats;
    }
}
