<?php
/* require_once __DIR__ . '/config/conexion.php';

class UsuarioEditarPerfil {
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion();
    }

    public function getUsuarioPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function emailExiste($email, $id_excluir = null) {
        if ($id_excluir) {
            $stmt = $this->conn->prepare("SELECT id FROM usuario WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $id_excluir);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
        }
        
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0;
    }

   public function actualizarPerfil($id, $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password_hash = null) {
    if ($password_hash) {
        $stmt = $this->conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=?, password=? WHERE id=?");
        $stmt->bind_param("ssissssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password_hash, $id);
    } else {
        $stmt = $this->conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=? WHERE id=?");
        $stmt->bind_param("ssisssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $id);
    }

    if (!$stmt->execute()) {
        error_log("Error al actualizar perfil: " . $stmt->error);
        return false;
    }

    return true;
}

} */
