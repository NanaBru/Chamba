<?php
session_start();

// Evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['email'])) {
    header("Location: ../../frontend/iniciarsesion.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "bdChamba");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Datos del usuario
$stmt = $conn->prepare("SELECT nombre, apellido FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resUser = $stmt->get_result();
$usuario = $resUser->fetch_assoc();

// Publicaciones (asegurate que tu tabla tenga la columna proveedor_email)
$publicaciones = $conn->query("
    SELECT titulo, descripcion, proveedor_cedula, fecha
    FROM publicaciones 
    ORDER BY fecha DESC
");

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Chamba</title>
    <link rel="stylesheet" href="../../frontend/estilos/inicio.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background: #f4f4f4;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #eeece7;
            color: white;
            
            border: black, 1px solid;
        }
        .logoDIV1 {
    display: flex;
    align-items: center;
}
        #menu .logo {
    width: 80px;
    height: auto;
    padding: 10px;
}
        nav h1 {
            margin-left: 10px;
            color: #67795c;
        }
        #navLinks {
            list-style: none;
            display: flex;
            gap: 15px;
        }
        #navLinks li a {
            text-decoration: none;
            color: #67795c;
            font-weight: bold;
        }
        .hamburguesa {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }
        .hamburguesa div {
            width: 25px;
            height: 3px;
            background: white;
            margin: 4px;
        }
        main {
            padding: 20px;
        }
        .publicaciones {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h4 {
            margin-bottom: 10px;
            color: #476a30;
        }
        .card p {
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            #navLinks {
                display: none;
                flex-direction: column;
                background: #476a30;
                padding: 10px;
            }
            #navLinks.show {
                display: flex;
            }
            .hamburguesa {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <nav id="menu">
        <div class="logoDIV1">
            <img class="logo" src="../../frontend/img/logopng.png" alt="">
            <h1 class="logoNombre">Chamba</h1>
        </div>
        <div class="LogoDIV2">
            <div class="hamburguesa" onclick="toggleMenu()">
                <div></div><div></div><div></div>
            </div>
            <ul id="navLinks">
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="../usuario/perfil.php">Mi Perfil</a></li>
                <li><a href="../usuario/logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <h2>¡Bienvenido, <?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?>!</h2>
        <h3>Publicaciones disponibles</h3>

        <section class="publicaciones">
            <?php if ($publicaciones && $publicaciones->num_rows > 0): ?>
                <?php while ($pub = $publicaciones->fetch_assoc()): ?>
                    <article class="card">
                        <h4><?php echo htmlspecialchars($pub['titulo']); ?></h4>
                        <p><?php echo htmlspecialchars($pub['descripcion']); ?></p>
                        <small>Publicado por: <?php echo htmlspecialchars($pub['proveedor_email']); ?> el <?php echo $pub['fecha']; ?></small>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay publicaciones aún.</p>
            <?php endif; ?>
        </section>
    </main>

    <script>
        function toggleMenu() {
            document.getElementById("navLinks").classList.toggle("show");
        }
    </script>
</body>
</html>
