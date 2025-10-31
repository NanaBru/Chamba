<?php
// Variables: $publicacion, $autor, $calificaciones, $promedioEstrellas
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/verPublicacion.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title><?= htmlspecialchars($publicacion['titulo']) ?> - Chamba</title>
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
                <li><a href="/chamba/web/router.php?page=perfil">Perfil</a></li>
                <li><a href="/chamba/web/router.php?page=crear-publicacion">Crear Publicación</a></li>
                <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="/chamba/web/router.php?page=sesion">Iniciar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </div>   
</nav>

<main class="publicacion-main">
    <div class="publicacion-container">
        
        <!-- Imagen de la publicación -->
        <div class="publicacion-imagen">
            <?php if (!empty($publicacion['imagen'])): ?>
                <img src="/chamba/web/datos/publicasiones/<?= htmlspecialchars($publicacion['imagen']) ?>" alt="<?= htmlspecialchars($publicacion['titulo']) ?>">
            <?php else: ?>
                <div class="sin-imagen">Sin imagen</div>
            <?php endif; ?>
        </div>

        <!-- Detalles de la publicación -->
        <div class="publicacion-detalles">
            <h1><?= htmlspecialchars($publicacion['titulo']) ?></h1>
            
            <div class="precio-rating">
                <span class="precio">$<?= number_format($publicacion['precio'], 2) ?></span>
                <div class="rating">
                    <span class="estrellas">★ <?= $promedioEstrellas['promedio'] ?></span>
                    <span class="total-reviews">(<?= $promedioEstrellas['total_calificaciones'] ?> calificaciones)</span>
                </div>
            </div>

            <div class="autor-info">
                <div class="autor-avatar">
                    <?php if (!empty($autor['foto_perfil'])): ?>
                        <img src="/chamba/web/datos/usuarios/<?= htmlspecialchars($autor['foto_perfil']) ?>" alt="<?= htmlspecialchars($autor['nombre']) ?>">
                    <?php else: ?>
                        <span class="inicial"><?= strtoupper(substr($autor['nombre'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <h3><?= htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) ?></h3>
                    <p class="fecha-publicacion">Publicado el <?= date('d/m/Y', strtotime($publicacion['fecha'])) ?></p>
                </div>
            </div>

            <div class="descripcion">
                <h2>Descripción</h2>
                <p><?= nl2br(htmlspecialchars($publicacion['descripcion'])) ?></p>
            </div>

            <div class="acciones">
                <a href="/chamba/web/router.php?page=chat&contacto=<?= $publicacion['usuario_id'] ?>" class="btn-contactar">Contactar</a>
                <a href="/chamba/web/router.php?page=inicio" class="btn-volver">Volver al inicio</a>
            </div>
        </div>
    </div>

    <!-- Calificaciones -->
    <div class="calificaciones-section">
        <h2>Calificaciones y Comentarios</h2>

        <?php if (empty($calificaciones)): ?>
            <p class="sin-calificaciones">Esta publicación aún no tiene calificaciones.</p>
        <?php else: ?>
            <div class="calificaciones-lista">
                <?php foreach ($calificaciones as $cal): ?>
                    <div class="calificacion-item">
                        <div class="cal-header">
                            <div class="cal-usuario">
                                <div class="cal-avatar">
                                    <?php if (!empty($cal['foto_perfil'])): ?>
                                        <img src="/chamba/web/datos/usuarios/<?= htmlspecialchars($cal['foto_perfil']) ?>" alt="<?= htmlspecialchars($cal['nombre']) ?>">
                                    <?php else: ?>
                                        <span><?= strtoupper(substr($cal['nombre'], 0, 1)) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4><?= htmlspecialchars($cal['nombre'] . ' ' . $cal['apellido']) ?></h4>
                                    <p class="cal-fecha"><?= date('d/m/Y H:i', strtotime($cal['fecha'])) ?></p>
                                </div>
                            </div>
                            <div class="cal-estrellas">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="<?= $i <= $cal['estrellas'] ? 'activa' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if (!empty($cal['comentario'])): ?>
                            <p class="cal-comentario"><?= nl2br(htmlspecialchars($cal['comentario'])) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
