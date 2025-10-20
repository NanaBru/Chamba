<?php
require_once __DIR__ . '/../config/conexion.php';

class ModeloEditarPerfil {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion();
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

    public function actualizarPerfil($id, $nombre, $apellido, $edad, $telefono, $email, $password_hash = null) {
        if ($password_hash) {
            $stmt = $this->conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("ssisssi", $nombre, $apellido, $edad, $telefono, $email, $password_hash, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=? WHERE id=?");
            $stmt->bind_param("ssissi", $nombre, $apellido, $edad, $telefono, $email, $id);
        }
        return $stmt->execute();
    }
}
