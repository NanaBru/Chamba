<?php
require_once __DIR__ . "/../config/conexion.php";

class ModeloPerfil {

    public static function obtenerUsuarioPorEmail($email) {
        $conn = Conexion::getConexion();
        $sql = "SELECT nombre, apellido, edad, telefono, email, foto_perfil
                FROM usuario
                WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $usuario = $res->fetch_assoc();
        $conn->close();
        return $usuario;
    }

    public static function actualizarFotoPerfil($email, $archivo) {
        $conn = Conexion::getConexion();
        $sql = "UPDATE usuario SET foto_perfil=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $archivo, $email);
        $ok = $stmt->execute();
        $conn->close();
        return $ok;
    }
}
