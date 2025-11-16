<?php
// Variables esperadas: $usuario, $tieneFoto, $fotoPath, $inicial, $misPublicaciones, $esMiPerfil
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/perfil.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title><?= $esMiPerfil ? 'Mi Perfil' : 'Perfil de ' . htmlspecialchars($usuario['nombre']) ?> - Chamba</title>
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
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <li><a href="/chamba/web/router.php?page=chat">Chat</a></li>
                <li><a href="/chamba/web/router.php?page=perfil">Mi Perfil</a></li>
                <li><a href="/chamba/web/router.php?page=crear-publicacion">Crear Publicación</a></li>
                <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="/chamba/web/router.php?page=sesion">Iniciar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </div>   
</nav>

<main class="perfil-main">
  
  <!-- Sección del perfil del usuario -->
  <section class="perfil-usuario">
    <div class="perfil-header">
      <div class="avatar-wrapper">
        <?php if ($tieneFoto): ?>
            <img src="<?= htmlspecialchars($fotoPath) ?>?v=<?= time() ?>" alt="Foto de perfil">
        <?php else: ?>
            <span class="inicial"><?= htmlspecialchars($inicial) ?></span>
        <?php endif; ?>
      </div>
      
      <div class="perfil-info">
        <?php if ($usuario): ?>
          <h2><?= htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']) ?></h2>
          <p><strong>Edad:</strong> <?= htmlspecialchars($usuario['edad']) ?></p>
          
          <?php if ($esMiPerfil): ?>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
          <?php endif; ?>
          
          <div class="perfil-actions">
            <?php if ($esMiPerfil): ?>
              <form id="formFoto" action="/chamba/web/router.php?page=perfil" method="post" enctype="multipart/form-data" style="display:inline-block;">
                <input type="file" name="foto_perfil" id="foto" style="display:none;" onchange="document.getElementById('formFoto').submit();">
                <button type="button" class="upload-btn" onclick="document.getElementById('foto').click();">
                  <?= $tieneFoto ? 'Cambiar foto' : 'Subir foto'; ?>
                </button>
              </form>
              <a href="/chamba/web/router.php?page=editar-perfil" class="edit-btn">Editar Perfil</a>
            <?php else: ?>
              <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="/chamba/web/router.php?page=chat&contacto=<?= $usuario['id'] ?>" class="btn-contactar">
                  💬 Enviar mensaje
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <p style="color:red;">Datos de usuario no disponibles.</p>
        <?php endif; ?>
      </div>

      <!-- Descripción del usuario -->
      <div class="perfil-descripcion">
        <?php if (!empty($usuario['descripcion'])): ?>
          <p><?= nl2br(htmlspecialchars($usuario['descripcion'])) ?></p>
        <?php else: ?>
          <p class="sin-descripcion">
            <?= $esMiPerfil ? 'Aún no has agregado una descripción.' : 'Este usuario aún no ha agregado una descripción.' ?>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Publicaciones del usuario -->
  <section class="perfil-publicaciones">
    <h3><?= $esMiPerfil ? 'Mis Publicaciones' : 'Publicaciones de ' . htmlspecialchars($usuario['nombre']) ?></h3>

    <?php if (!empty($misPublicaciones)): ?>
      <div class="publicaciones">
        <?php foreach ($misPublicaciones as $p): ?>
          <div class="card" onclick="window.location.href='/chamba/web/router.php?page=publicacion&id=<?= urlencode((string)($p['id'] ?? '')) ?>'">
            <?php if (!empty($p['imagen'])): ?>
              <img src="/chamba/web/datos/publicasiones/<?= htmlspecialchars($p['imagen']) ?>" alt="imagen de la publicación">
            <?php endif; ?>

            <h4><?= htmlspecialchars($p['titulo'] ?? 'Sin título') ?></h4>

            <?php if (!empty($p['precio'])): ?>
              <span class="precio">$<?= htmlspecialchars($p['precio']) ?></span>
            <?php endif; ?>

            <?php if (!empty($p['descripcion'])): ?>
              <p><?= nl2br(htmlspecialchars(mb_substr($p['descripcion'], 0, 100))) ?><?= strlen($p['descripcion']) > 100 ? '...' : '' ?></p>
            <?php endif; ?>

            <small>
              <?= htmlspecialchars($p['fecha'] ? "Publicada: " . date('d/m/Y', strtotime($p['fecha'])) : "Sin fecha") ?>
            </small>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="sin-publicaciones">
        <?= $esMiPerfil ? 'Aún no has creado publicaciones.' : 'Este usuario no tiene publicaciones aún.' ?>
      </p>
    <?php endif; ?>
  </section>

</main>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
