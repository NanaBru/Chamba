<?php
require_once __DIR__ . '/../config/conexion.php';

class Login {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function verificarUsuario($email, $passwordIngresada) {
        // Traer solo el hash de la contraseña desde la base
        $sql = "SELECT password FROM usuario WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            return false; // no existe ese email
        }

        $fila = $resultado->fetch_assoc();
        $passwordHash = $fila["password"];

        // Comparar contraseñas
        return password_verify($passwordIngresada, $passwordHash);
    }
}
