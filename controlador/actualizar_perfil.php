<?php
session_start();

// Redirigir al login si no hay sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../sesion.php");
    exit;
}

// Verificar que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: perfil.php");
    exit;
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "chambaBD");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$email_sesion = $_SESSION['email'];

// Obtener y validar datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$edad = intval($_POST['edad'] ?? 0);
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validaciones básicas
$errores = [];

if (empty($nombre)) $errores[] = "El nombre es obligatorio";
if (empty($apellido)) $errores[] = "El apellido es obligatorio";
if ($edad <= 0 || $edad > 120) $errores[] = "La edad debe ser un número válido entre 1 y 120";
if (empty($telefono)) $errores[] = "El teléfono es obligatorio";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "El email no tiene un formato válido";

// Verificar si el nuevo email ya existe (si cambió)
if ($email !== $email_sesion) {
    $stmt_check = $conn->prepare("SELECT id FROM usuario WHERE email = ? AND email != ?");
    $stmt_check->bind_param("ss", $email, $email_sesion);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        $errores[] = "El email ya está registrado por otro usuario";
    }
    $stmt_check->close();
}

// Si hay errores, redirigir con mensaje
if (!empty($errores)) {
    $_SESSION['error_mensaje'] = implode(", ", $errores);
    header("Location: perfil.php");
    exit;
}

// Actualizar datos
try {
    if (!empty($password)) {
        // Actualizar también la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=?, password=? WHERE email=?");
        $stmt->bind_param("ssissss", $nombre, $apellido, $edad, $telefono, $email, $password_hash, $email_sesion);
    } else {
        // Sin cambiar la contraseña
        $stmt = $conn->prepare("UPDATE usuario SET nombre=?, apellido=?, edad=?, telefono=?, email=? WHERE email=?");
        $stmt->bind_param("ssisss", $nombre, $apellido, $edad, $telefono, $email, $email_sesion);
    }

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Actualizar email en sesión si cambió
            if ($email !== $email_sesion) {
                $_SESSION['email'] = $email;
            }
            $_SESSION['success_mensaje'] = "Perfil actualizado correctamente";
        } else {
            $_SESSION['info_mensaje'] = "No se realizaron cambios en el perfil";
        }
    } else {
        throw new Exception("Error al actualizar: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error_mensaje'] = "Error al actualizar el perfil: " . $e->getMessage();
}

$conn->close();

// Redirigir a perfil.php
header("Location: ../vista/usuario/perfil.php");
exit;
?>