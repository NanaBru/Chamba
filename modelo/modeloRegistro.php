<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function registrar($nombre, $apellido, $edad, $telefono, $email, $password) {
        // Verificar si ya existe un usuario con el mismo teléfono o email
        $sql_check = "SELECT 1 FROM usuario WHERE telefono = ? OR email = ?";
        $stmt = $this->conexion->prepare($sql_check);
        $stmt->bind_param("ss", $telefono, $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            return false; // Ya existe un usuario con ese teléfono o email
            
        }

        // Encriptar contraseña e insertar el usuario
        $password_encriptada = password_hash($password, PASSWORD_BCRYPT);
        $sql_insert = "INSERT INTO usuario (nombre, apellido, edad, telefono, email, password) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql_insert);
        $stmt->bind_param("ssisss", $nombre, $apellido, $edad, $telefono, $email, $password_encriptada);

        return $stmt->execute();
    }
}
