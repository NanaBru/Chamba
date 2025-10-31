<?php
// web/modelo/Usuario.php
require_once __DIR__ . '/config/conexion.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    // ==============================
    // 🟢 REGISTRAR NUEVO USUARIO
    // ==============================
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

            // Insertar usuario
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

    // ==============================
    // 🟢 LOGIN
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
    // 🟢 OBTENER DATOS DE USUARIO POR ID
    // ==============================
   // OBTENER DATOS DE USUARIO POR ID (incluye descripción)
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
    // 🟢 ACTUALIZAR FOTO DE PERFIL
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
    // 🟢 ACTUALIZAR DATOS DEL PERFIL
    // ==============================
   // ACTUALIZAR PERFIL COMPLETO (incluye descripción, email y opcionalmente password)
// ACTUALIZAR PERFIL COMPLETO (incluye descripción, email y opcionalmente password)
// ACTUALIZAR PERFIL COMPLETO
// ACTUALIZAR PERFIL COMPLETO
public function actualizarPerfil($id, $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password_hash = null) {
    $conn = $this->conexion->getConexion();
    
    if ($password_hash) {
        $stmt = $conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=?, password=? WHERE id=?");
        $stmt->bind_param("ssissssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password_hash, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=? WHERE id=?");
        $stmt->bind_param("sisssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $id);
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


// ACTUALIZAR PERFIL COMPLETO - VERSIÓN SIMPLIFICADA
// ACTUALIZAR PERFIL COMPLETO (devuelve array con success y mensaje)
// ACTUALIZAR PERFIL COMPLETO (devuelve array con success y mensaje)
public function actualizarPerfilCompleto($usuario_id, $nombre, $apellido, $edad, $telefono, $email, $descripcion, $password = null) {
    try {
        $conn = $this->conexion->getConexion();

        // Verificar email duplicado
        $sql = "SELECT id FROM usuario WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $usuario_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return ['success' => false, 'mensaje' => 'Email ya registrado por otro usuario.'];
        }

        // Verificar teléfono duplicado
        $sql = "SELECT id FROM usuario WHERE telefono = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $telefono, $usuario_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return ['success' => false, 'mensaje' => 'Teléfono ya registrado por otro usuario.'];
        }

        // Actualizar
        if (!empty($password)) {
            $passHash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=?, password=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            // 8 parámetros: s s i s s s s i
            $stmt->bind_param("ssissssi", $nombre, $apellido, $edad, $telefono, $email, $descripcion, $passHash, $usuario_id);
        } else {
            $sql = "UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, descripcion=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            // 7 parámetros: s s i s s s i
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







    // ==============================
    // 🟢 OBTENER PUBLICACIONES DEL USUARIO
    // ==============================
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

// ==============================
// 🟢 ACTUALIZAR FOTO DE PERFIL (por ID)
// ==============================
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


// OBTENER USUARIO POR EMAIL
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
