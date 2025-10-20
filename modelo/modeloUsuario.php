<?php
require_once __DIR__ . '/../config/conexion.php';

class ModeloUsuario {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion();
    }

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT nombre, apellido FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
}

