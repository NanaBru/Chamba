<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../frontend/iniciarsesion.html");
    exit;
}

// Conexión a la BD chambaBD
$conn = new mysqli("localhost", "root", "", "chambaBD");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del usuario logueado
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id, nombre, apellido FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$usuario = $res->fetch_assoc();

if (!$usuario) {
    die("Usuario no encontrado.");
}

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = trim($_POST['precio'] ?? '');
    $imagenRuta  = null;

    // Validar precio (puede ser decimal, mayor o igual a 0)
    if ($precio === '' || !is_numeric($precio) || $precio < 0) {
        $mensaje = "Ingrese un precio válido.";
    }

    // Procesar imagen si se envió
    if (empty($mensaje) && !empty($_FILES['imagen']['name'])) {
        $carpeta = "../../uploads/";  // crea esta carpeta con permisos de escritura
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

        $nombreArchivo = time() . "_" . basename($_FILES['imagen']['name']);
        $destino = $carpeta . $nombreArchivo;

        $tipo = mime_content_type($_FILES['imagen']['tmp_name']);
        if (preg_match('/image\/(jpeg|png|gif)/', $tipo)) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                // Guardamos la ruta relativa para mostrarla en la web
                $imagenRuta = "uploads/" . $nombreArchivo;
            } else {
                $mensaje = "Error al subir la imagen.";
            }
        } else {
            $mensaje = "Formato de imagen no válido (solo jpg, png, gif).";
        }
    }

    if ($titulo && $descripcion && empty($mensaje)) {
        $ins = $conn->prepare(
            "INSERT INTO publicaciones (usuario_id, titulo, descripcion, imagen, precio)
             VALUES (?,?,?,?,?)"
        );
        $ins->bind_param(
            "isssd",
            $usuario['id'],
            $titulo,
            $descripcion,
            $imagenRuta,
            $precio
        );

        if ($ins->execute()) {
            header("Location: inicio.php");
            exit;
        } else {
            $mensaje = "Error al guardar la publicación.";
        }
    } elseif (!$titulo || !$descripcion) {
        $mensaje = "Completa título y descripción.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../vista/estilos/stylesNav.css">
<link rel="stylesheet" href="../../vista/estilos/crearpubli.css">
<link rel="shortcut icon" href="../../vista/img/logopng.png" type="image/x-icon">
<title>Nueva Publicación</title>

</head>
<body>

    
    <nav id="menu">
    <div class="logoDIV1">
        <img class="logo" src="../../vista/img/logopng.png" alt="logo">
        <a href="inicio.php"><h1 class="logoNombre">Chamba</h1></a>
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

    <form method="post" enctype="multipart/form-data">
    <h2>Crear publicación</h2>
  
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" maxlength="150" required>

    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" id="descripcion" rows="6" required></textarea>

    <label for="precio">Precio (en pesos):</label>
    <input type="number" name="precio" id="precio" step="0.01" min="0" required>

    <label for="imagen">Imagen (opcional):</label>
    <input type="file" name="imagen" accept="image/*">

    <button type="submit">Publicar</button>

    <?php if($mensaje): ?>
        <div class="msg"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
</form>

</body>
</html>   
