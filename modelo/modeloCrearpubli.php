<?php
require_once __DIR__ . '/../config/conexion.php';

class modeloPublicaciones {

    public static function crearPublicacion($usuario_id, $titulo, $descripcion, $imagen, $precio) {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare(
            "INSERT INTO publicaciones (usuario_id, titulo, descripcion, imagen, precio)
             VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param("isssd", $usuario_id, $titulo, $descripcion, $imagen, $precio);
        $res = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $res;
    }

}
