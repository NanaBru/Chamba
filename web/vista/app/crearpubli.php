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
            <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>   
</nav>

<main class="crear-publicacion-main">
    <h1 class="page-title">Crear Nueva Publicación</h1>
    
    <form method="post" enctype="multipart/form-data" class="form-publicacion">
        
        <div class="form-group">
            <label for="titulo">Título del Servicio</label>
            <input type="text" name="titulo" id="titulo" maxlength="150" placeholder="Ej: Clases de guitarra para principiantes" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="6" placeholder="Describe tu servicio en detalle..." required></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="precio">Precio en pesos</label>
                <input type="number" name="precio" id="precio" step="0.01" min="0" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen del Servicio</label>
                <input type="file" name="imagen" id="imagen" accept="image/*">
                <small>Se convertirá automáticamente a WebP para optimización</small>
            </div>
        </div>

        <button type="submit" class="btn-publicar">Publicar Servicio</button>

        <?php if(!empty($mensaje)): ?>
            <div class="msg <?= strpos($mensaje, 'éxito') !== false ? 'msg-success' : 'msg-error' ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
    </form>
</main>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
