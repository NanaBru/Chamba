<?php
session_start();

// Evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redirigir si no hay sesión iniciada
if (!isset($_SESSION['email'])) {
    header("Location: ../../frontend/iniciarsesion.html");
    exit;
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "chambaBD");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Datos del usuario actual
$stmt = $conn->prepare("SELECT nombre, apellido FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resUser = $stmt->get_result();
$usuario = $resUser->fetch_assoc();

// Publicaciones con nombre del autor + imagen + precio
$publicaciones = $conn->query("
    SELECT p.titulo, p.descripcion, p.imagen, p.precio, p.fecha,
           u.nombre, u.apellido
    FROM publicaciones p
    JOIN usuario u ON p.usuario_id = u.id
    ORDER BY p.fecha DESC
");

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio - Chamba</title>
<link rel="stylesheet" href="../../vista/estilos/inicio.css">
<link rel="stylesheet" href="../../vista/estilos/stylesNav.css">
</head>
<body>

<nav id="menu">
    <div class="logoDIV1">
        <img class="logo" src="../../vista/img/logopng.png" alt="logo">
        <a href="index.php"><h1 class="logoNombre">Chamba</h1></a>
    </div>
    <div class="LogoDIV2">
        <div class="hamburguesa" onclick="toggleMenu()">
            <div></div><div></div><div></div>
        </div>
        <ul id="navLinks">
            <li><a href="inicio.php">Inicio</a></li>
            <li><a href="../usuario/perfil.php">Mi Perfil</a></li>
            <li><a href="crearpubli.php">Crear Publicación</a></li>
            <li><a href="../usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<main>
    <h3>Publicaciones disponibles</h3>

    <section class="publicaciones">
        <?php if ($publicaciones && $publicaciones->num_rows > 0): ?>
            <?php while ($pub = $publicaciones->fetch_assoc()): ?>
                <article class="card" onclick="mostrarNotificacion()">
                    <?php if (!empty($pub['imagen'])): ?>
                        <!-- Mostrar imagen subida -->
                        <img src="../../<?php echo htmlspecialchars($pub['imagen']); ?>" alt="Imagen de la publicación">
                    <?php endif; ?>
                    <h4><?php echo htmlspecialchars($pub['titulo']); ?></h4>
                    <span class="precio">
                        <?php echo $pub['precio'] !== null ? '$ '.number_format($pub['precio'],2) : ''; ?>
                    </span>
                    <p><?php echo nl2br(htmlspecialchars($pub['descripcion'])); ?></p>
                    <small>
                        Publicado por: <?php echo htmlspecialchars($pub['nombre'].' '.$pub['apellido']); ?>
                        el <?php echo $pub['fecha']; ?>
                    </small>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay publicaciones aún.</p>
        <?php endif; ?>
    </section>
</main>

<script>
function toggleMenu(){
    document.getElementById("navLinks").classList.toggle("show");
}

// Función para mostrar notificación del navegador
function mostrarNotificacion() {
   
    alert("Función en desarrollo");
}
</script>
</body>
</html>