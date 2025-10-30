<?php
// web/modelo/Usuario.php
require_once __DIR__ . '/config/conexion.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // REGISTRAR
    public function registrarUsuario($nombre, $apellido, $edad, $telefono, $email, $password) {
        try {
            $conn = $this->conexion->getConexion();

            // Verificar si ya existe email o teléfono
            $check = $conn->prepare("SELECT id FROM usuario WHERE email = ? OR telefono = ?");
            $check->bind_param("ss", $email, $telefono);
            $check->execute();
            $res = $check->get_result();
            if ($res && $res->num_rows > 0) {
                return ['success' => false, 'mensaje' => 'El correo o teléfono ya está registrado.'];
            }

            // Hash de contraseña
            $passHash = password_hash($password, PASSWORD_BCRYPT);

            // Insertar
            $sql = "INSERT INTO usuario (nombre, apellido, edad, telefono, email, password)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisss", $nombre, $apellido, $edad, $telefono, $email, $passHash);
            $stmt->execute();

            return ['success' => true, 'mensaje' => 'Usuario registrado correctamente.'];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }

    // LOGIN
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

    // OBTENER USUARIO POR EMAIL (para inicio, perfil, etc.)
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

    // OBTENER USUARIO POR ID (opcional)
    public function getUserById(int $id) {
        try {
            $conn = $this->conexion->getConexion();
            $sql = "SELECT id, nombre, apellido, edad, telefono, email, foto_perfil, fecha_creacion, ultima_modificasion
                    FROM usuario WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) return null;
            $stmt->bind_param("i", $id);
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

    // PERFIL por email (alias simple, si lo prefieres)
    public function obtenerPerfil(string $email) {
        return $this->getUserByEmail($email);
    }

    // ACTUALIZAR FOTO PERFIL
    public function actualizarFotoPerfil($email, $nombreArchivo) {
        try {
            $conn = $this->conexion->getConexion();
            $stmt = $conn->prepare("UPDATE usuario SET foto_perfil = ? WHERE email = ?");
            $stmt->bind_param("ss", $nombreArchivo, $email);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Devuelve el perfil completo por email o null si no existe
    public function getPerfilPorEmail(string $email): ?array {
        try {
            $conn = $this->conexion->getConexion();
            $sql = "SELECT id, nombre, apellido, edad, telefono, email, foto_perfil, fecha_creacion, ultima_modificasion
                    FROM usuario
                    WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) return null;
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                return $res->fetch_assoc();
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    // ACTUALIZAR DATOS BÁSICOS DE PERFIL (opcional)
    public function actualizarDatosPerfil($email, $nombre, $apellido, $edad, $telefono) {
        try {
            $conn = $this->conexion->getConexion();
            $stmt = $conn->prepare("UPDATE usuario
                                    SET nombre = ?, apellido = ?, edad = ?, telefono = ?
                                    WHERE email = ?");
            $stmt->bind_param("ssiss", $nombre, $apellido, $edad, $telefono, $email);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
