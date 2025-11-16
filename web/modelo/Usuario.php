<?php
// web/modelo/Usuario.php
require_once __DIR__ . '/config/conexion.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // ==============================
    // REGISTRAR NUEVO USUARIO (rol por defecto: 'usuario')
    // ==============================
    public function registrarUsuario($nombre, $apellido, $edad, $telefono, $email, $password_plano, $descripcion = null, $cedula = null) {
    $conn = $this->conexion->getConexion();

    // Duplicados
    $stmt = $conn->prepare("SELECT id FROM usuario WHERE email = ? OR telefono = ?");
    $stmt->bind_param("ss", $email, $telefono);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return ['success' => false, 'mensaje' => 'Email o teléfono ya registrado'];
    }

    // Verificar cédula duplicada (si se proporciona)
    if (!empty($cedula)) {
        $stmt = $conn->prepare("SELECT id FROM usuario WHERE cedula = ?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return ['success' => false, 'mensaje' => 'Cédula ya registrada'];
        }
    }

    $hash = password_hash($password_plano, PASSWORD_BCRYPT);
    $rol = 'usuario';

    $sql = "INSERT INTO usuario (nombre, apellido, edad, telefono, cedula, email, password, rol, descripcion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssss", $nombre, $apellido, $edad, $telefono, $cedula, $email, $hash, $rol, $descripcion);

    if ($stmt->execute()) {
        return ['success' => true, 'id' => $stmt->insert_id];
    }
    return ['success' => false, 'mensaje' => 'No se pudo registrar'];
}


    // ==============================
    // LOGIN (no exige rol; devuelve el array del usuario si es válido)
    // ==============================
    public function iniciarSesion($email, $password) {
        try {
            $conn = $this->conexion->getConexion();
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if (!$result || $result->num_rows === 0) return false;

            $usuario = $result->fetch_assoc();
            return password_verify($password, $usuario['password']) ? $usuario : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // ==============================
    // OBTENER DATOS DE USUARIO POR ID (incluye descripción)
    // ==============================
    public function obtenerDatosUsuario($usuario_id) {
        try {
            $conn = $this->conexion->getConexion();
            $sql = "SELECT id, nombre, apellido, edad, telefono, email, foto_perfil, descripcion
                    FROM usuario WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        } catch (Exception $e) {
            return null;
        }
    }

    // ==============================
    // ACTUALIZAR FOTO DE PERFIL
    // ==============================
    public function actualizarFotoPerfil($usuario_id, $nombreArchivo) {
        try {
            $conn = $this->conexion->getConexion();
            $stmt = $conn->prepare("UPDATE usuario SET foto_perfil = ? WHERE id = ?");
            $stmt->bind_param("si", $nombreArchivo, $usuario_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // ==============================
    // ACTUALIZAR DATOS DEL PERFIL
    // ==============================
    public function actualizarPerfil($id, $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password_hash = null) {
        $conn = $this->conexion->getConexion();
        if ($password_hash) {
            $stmt = $conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=?, password=? WHERE id=?");
            $stmt->bind_param("ssissssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password_hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=? WHERE id=?");
            $stmt->bind_param("ssisssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $id);
        }
        return $stmt->execute();
    }

    public function getUsuarioPorEmail($email) {
        $conn = $this->conexion->getConexion();
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function emailExiste($email, $id_excluir = null) {
        $conn = $this->conexion->getConexion();
        if ($id_excluir) {
            $stmt = $conn->prepare("SELECT id FROM usuario WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $id_excluir);
        } else {
            $stmt = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0;
    }

    // Actualizar perfil completo (respuesta con array)
    public function actualizarPerfilCompleto($usuario_id, $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password = null) {
        try {
            $conn = $this->conexion->getConexion();

            $sql = "SELECT id FROM usuario WHERE email = ? AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $email, $usuario_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                return ['success' => false, 'mensaje' => 'Email ya registrado por otro usuario.'];
            }

            $sql = "SELECT id FROM usuario WHERE telefono = ? AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $telefono, $usuario_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                return ['success' => false, 'mensaje' => 'Teléfono ya registrado por otro usuario.'];
            }

            if (!empty($password)) {
                $passHash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=?, password=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssissssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $passHash, $usuario_id);
            } else {
                $sql = "UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssisssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $usuario_id);
            }

            if ($stmt->execute() && $stmt->affected_rows >= 0) {
                return ['success' => true, 'mensaje' => 'Perfil actualizado correctamente.'];
            }
            return ['success' => false, 'mensaje' => 'No se pudo actualizar: ' . $conn->error];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }

    // Publicaciones del usuario
    public function obtenerPublicacionesPorUsuario($usuario_id) {
        try {
            $conn = $this->conexion->getConexion();
            $sql = "SELECT id, titulo, descripcion, imagen, precio, fecha_creacion AS fecha
                    FROM publicaciones 
                    WHERE usuario_id = ? 
                    ORDER BY fecha_creacion DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Foto de perfil por ID
    public function actualizarFotoPerfilPorId($id, $foto) {
        try {
            $conn = $this->conexion->getConexion();
            $sql = "UPDATE usuario SET foto_perfil = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $foto, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Obtener usuario por email (solo datos básicos)
    public function getUserByEmail(string $email) {
        try {
            $conn = $this->conexion->getConexion();
            $sql = "SELECT id, nombre, apellido, edad, telefono, email, foto_perfil, fecha_creacion, ultima_modificasion
                    FROM usuario WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) return null;
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}
