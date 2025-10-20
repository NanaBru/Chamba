<?php
require_once __DIR__ . "/../../controlador/PerfilControler.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../vista/estilos/stylesNav.css">
<link rel="stylesheet" href="../../vista/estilos/perfil.css">
<link rel="shortcut icon" href="../../vista/img/logopng.png" type="image/x-icon">
<title>Perfil</title>
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
            <li><a href="../app/crearpubli.php">Crear Publicación</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
     </div>   
</nav>


<h2> Datos de Perfil</h2>

<main class="profile-container">

  <div class="fotoPerfil">
    <div class="avatar-wrapper">
      <?php if ($tieneFoto): ?>
          <img src="<?= htmlspecialchars($fotoPath) ?>" alt="Foto de perfil">
      <?php else: ?>
          <span class="inicial"><?= htmlspecialchars($inicial) ?></span>
      <?php endif; ?>
    </div>

    <form id="formFoto" action="../../controlador/perfilController.php" method="post" enctype="multipart/form-data">
      <input type="file" name="foto_perfil" id="foto" style="display:none;"
             onchange="document.getElementById('formFoto').submit();">
      <button type="button" class="upload-btn" onclick="document.getElementById('foto').click();">
          <?= $tieneFoto ? 'Cambiar foto' : 'Subir foto'; ?>
      </button>
    </form>
  </div>

 <div class="profile-content">
    <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre'].' '.$usuario['apellido']) ?></p>
    <p><strong>Edad:</strong> <?= htmlspecialchars($usuario['edad']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
    <a href="editarPerfil.php" class="upload-btn">Editar Perfil</a>
  </div>
  
</main>     


<script src="../js/script.js"></script>


</body>
</html>

