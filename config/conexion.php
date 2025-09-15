<?php
class Conexion {
    public static function getConexion() {
        $conexion = new mysqli("localhost", "root", "", "chambaBD");
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        return $conexion;
    }
}
