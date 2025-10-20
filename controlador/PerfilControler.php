<?php
// Redirigir si no hay sesión
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../sesion.php");
    exit;
}

// Conexión a la BD
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "chambaBD";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$sql = "SELECT nombre, apellido, edad, telefono, email, foto_perfil
        FROM usuario
        WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit;
}
$usuario = $result->fetch_assoc();
$conn->close();

$inicial = strtoupper(substr($usuario['nombre'], 0, 1));
$foto    = $usuario['foto_perfil'] ?? '';
$fotoPath = "../../datos/" . $foto;
$tieneFoto = !empty($foto) && file_exists($fotoPath);
?>