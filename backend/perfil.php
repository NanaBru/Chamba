<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['email'])) {
    header("Location: ../frontend/iniciarsesion.html");
    exit;
}

// Conexión a la BD
$servername = "localhost";
$username = "root";   // o el que uses
$password = "";       // tu contraseña
$dbname = "bdChamba";   // nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Consultar datos del usuario logueado
$sql = "SELECT Cedula, nombre, apellido, edad, telefono, email 
        FROM usuario 
        WHERE Cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../frontend/estilos/perfil.css">
</head>
<body>

    <h1>Perfil del Usuario</h1>
    <p><strong>Cédula:</strong> <?php echo $usuario['Cedula']; ?></p>
    <p><strong>Nombre:</strong> <?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?></p>
    <p><strong>Edad:</strong> <?php echo $usuario['edad']; ?></p>
    <p><strong>Teléfono:</strong> <?php echo $usuario['telefono']; ?></p>
    <p><strong>Email:</strong> <?php echo $usuario['email']; ?></p>

    <br>
    <a href="editarPerfil.php">Editar Perfil</a> | 
    <a href="logout.php">Cerrar Sesión</a>

</body>
</html>
