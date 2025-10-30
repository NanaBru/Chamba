<?php
// La variable $mensaje viene del controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/crearpubli.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Nueva Publicación</title>
</head>
<body>

<nav id="menu">
    <div class="logoDIV1">
        <img class="logo" src="/chamba/web/vista/assets/img/logopng.png" alt="logo">
        <a href="/chamba/web/router.php?page=inicio"><h1 class="logoNombre">Chamba</h1></a>
    </div>
    <div class="LogoDIV2">
        <div class="hamburguesa" onclick="toggleMenu()">
            <div></div><div></div><div></div>
        </div>
        <ul id="navLinks">
            <li><a href="/chamba/web/router.php?page=inicio">Inicio</a></li>
            <li><a href="/chamba/web/router.php?page=perfil">Perfil</a></li>
            <li><a href="/chamba/web/app/usuario/logout.php">Cerrar Sesión</a></li>
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

        <?php if(!empty($mensaje)): ?>
            <div class="msg"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
    </form>
</section>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
