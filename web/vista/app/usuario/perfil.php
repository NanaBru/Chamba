<?php
// Este archivo NO necesita hacer require del controlador, lo llama el perfilController
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/perfil.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Perfil</title>
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
            <li><a href="/chamba/web/router.php?page=crear-publicacion">Crear Publicación</a></li>
            <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>   
</nav>

<h2>Datos de Perfil</h2>

<main class="profile-container">

    <div class="fotoPerfil">
        <div class="avatar-wrapper">
            <?php if ($tieneFoto): ?>
                <img src="<?= htmlspecialchars($fotoPath) ?>" alt="Foto de perfil">
            <?php else: ?>
                <span class="inicial"><?= htmlspecialchars($inicial) ?></span>
            <?php endif; ?>
        </div>
        <form id="formFoto" action="/chamba/web/controlador/perfilController.php" method="post" enctype="multipart/form-data">
            <input type="file" name="foto_perfil" id="foto" style="display:none;" onchange="document.getElementById('formFoto').submit();">
            <button type="button" class="upload-btn" onclick="document.getElementById('foto').click();">
                <?= $tieneFoto ? 'Cambiar foto' : 'Subir foto'; ?>
            </button>
        </form>
    </div>

    <div class="profile-content">
        <?php if ($usuario): ?>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']) ?></p>
            <p><strong>Edad:</strong> <?= htmlspecialchars($usuario['edad']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
            <a href="editarPerfil.php" class="upload-btn">Editar Perfil</a>
        <?php else: ?>
            <p style="color:red;">Datos de usuario no disponibles. Intenta cerrar sesión y volver a entrar.</p>
        <?php endif; ?>
    </div>
</main>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
