<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['email'])) {
    header("Location: ../../frontend/iniciarsesion.html");
    exit;
}

// Conexión a la BD
$servername = "localhost";
$username = "root";   
$password = "";       
$dbname = "bdChamba";  

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Consultar datos del usuario logueado usando el email
$sql = "SELECT Cedula, nombre, apellido, edad, telefono, email, rol
        FROM usuario 
        WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
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
    <link rel="stylesheet" href="../../frontend/estilos/stylesNav.css">
    <title>Perfil</title>
    <style>
        /* perfil.css */

/* Reset básico */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Body */
body {
    background-color: #f5f6fa;
    color: #333;
    min-height: 100vh;
    padding-bottom: 50px;
}

/* Navbar */
#menu {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #eeece7;
    color: #fff;
}

#menu .logoDIV1 {
    display: flex;
    align-items: center;
}

#menu .logo {
    width: 80px;
    height: auto;
}

#menu .logoNombre {
    font-size: 1.5rem;
    color: #fff;
    text-decoration: none;
    color: #67795c;
}

#navLinks {
    list-style: none;
    display: flex;
    gap: 20px;
}

#navLinks li a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
    color: #67795c;
}

#navLinks li a:hover {
    color: #e1b12c;
}

/* Contenido del perfil */
h2 {
    text-align: center;
    margin: 30px 0 20px;
    color: #2f3640;
}

.profile-container {
    width: 90%;
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
}

.profile-container p {
    font-size: 1.1rem;
    margin: 10px 0;
}

.profile-container strong {
    color: #2f3640;
}

/* Botón de editar */
.profile-container a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #2f3640;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.profile-container a:hover {
    background-color: #e1b12c;
    color: #2f3640;
}

/* Mensajes */
#mensaje {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 768px) {
    #navLinks {
        flex-direction: column;
        gap: 10px;
    }
    
    .profile-container {
        padding: 20px;
    }
}

    </style>
</head>
<body>

    <nav id="menu">
        <header class="logoDIV1">
            <img class="logo" src="../../frontend/img/logopng.png" alt="">
            <a href="../../index.html">
                <h1 class="logoNombre">Chamba</h1>
            </a>
        </header>

        <section class="LogoDIV2">
            <div class="hamburguesa" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <ul id="navLinks">
                <li><a href="../app/inicio.php">Inicio</a></li>
                <li><a href="https://youtu.be/dQw4w9WgXcQ">Sobre</a></li>
                <li><a href="https://youtu.be/dQw4w9WgXcQ">Contacto</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </section>
    </nav>

    <h2>Perfil del Usuario</h2>
    
   <div class="profile-container">
    <p><strong>Cédula:</strong> <?php echo $usuario['Cedula']; ?></p>
    <p><strong>Nombre:</strong> <?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?></p>
    <p><strong>Edad:</strong> <?php echo $usuario['edad']; ?></p>
    <p><strong>Teléfono:</strong> <?php echo $usuario['telefono']; ?></p>
    <p><strong>Email:</strong> <?php echo $usuario['email']; ?></p>
    <p><strong>Rol:</strong> <?php echo $usuario['rol']; ?></p>

    <a href="editarPerfil.php">Editar Perfil</a>
</div>


    <script>
        // Evitar mostrar datos desde caché al volver atrás
        if (window.performance && window.performance.navigation.type === 2) {
            window.location.reload(true);
        }
    </script>

</body>
</html>
