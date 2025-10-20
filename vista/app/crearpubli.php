<?php
// Invoca al controlador
require_once __DIR__ . "/../../controlador/crearpubliControler.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../vista/estilos/stylesNav.css">
<link rel="stylesheet" href="../../vista/estilos/crearpubli.css">
<link rel="shortcut icon" href="../../vista/img/logopng.png" type="image/x-icon">
<title>Nueva Publicación</title>
</head>
<body>

<nav id="menu">
    <div class="logoDIV1">
        <img class="logo" src="../../vista/img/logopng.png" alt="logo">
        <a href="../app/inicio.php"><h1 class="logoNombre">Chamba</h1></a>
    </div>
    <div class="LogoDIV2">
        <div class="hamburguesa" onclick="toggleMenu()">
            <div></div><div></div><div></div>
        </div>
        <ul id="navLinks">
            <li><a href="../app/inicio.php">Inicio</a></li>
            <li><a href="../usuario/perfil.php">Perfil</a></li>
            <li><a href="../usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>   
</nav>

<section class="crearpubli">
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
</section>

<script src="../js/script.js"></script>
</body>
</html>
