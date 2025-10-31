<?php
class Conexion {
    public static function getConexion() {
        $conexion = @new mysqli("localhost", "root", "", "chambabd");

        if ($conexion->connect_error) {
            throw new Exception("Error de conexión: " . $conexion->connect_error);
        }

        $conexion->set_charset("utf8mb4");

        return $conexion;
    }
}
