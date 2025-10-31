<?php
// web/vista/app/inicio.php
// Variables esperadas: $usuario (o null) y $publicaciones (array)
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/inicio.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/footer.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Inicio - Chamba</title>
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
            <li><a href="/chamba/web/router.php?page=crear-publicacion">Crear Publicación</a></li>
            <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>   
</nav>

<main>
    <section>
        <h2>Publicaciones disponibles</h2>

        <?php if (!empty($publicaciones)): ?>
            <div class="publicaciones">
                <?php foreach ($publicaciones as $p): ?>
                    <div class="card" onclick="window.location.href='/chamba/web/router.php?page=publicacion&id=<?= urlencode((string)($p['id'] ?? '')) ?>'">
                        <?php if (!empty($p['imagen'])): ?>
                            <img src="/chamba/web/datos/publicasiones/<?= htmlspecialchars($p['imagen']) ?>" alt="imagen de la publicación">
                        <?php endif; ?>

                        <h4><?= htmlspecialchars($p['titulo'] ?? 'Sin título') ?></h4>

                        <?php
                        // Render de estrellas (requiere que el modelo retorne 'rating' con AVG)
                        $rating = is_numeric($p['rating'] ?? null) ? (float)$p['rating'] : 0.0;
                        $full   = (int)floor($rating);
                        $half   = ($rating - $full) >= 0.5 ? 1 : 0;
                        $empty  = 5 - $full - $half;
                        ?>
                        <div class="stars" aria-label="Calificación <?= htmlspecialchars(number_format($rating,1)) ?> de 5">
                            <?php for ($i = 0; $i < $full; $i++): ?>
                                <span class="star full">★</span>
                            <?php endfor; ?>
                            <?php if ($half): ?>
                                <span class="star half">☆</span>
                            <?php endif; ?>
                            <?php for ($i = 0; $i < $empty; $i++): ?>
                                <span class="star empty">✩</span>
                            <?php endfor; ?>
                            <span class="rating-num">(<?= htmlspecialchars(number_format($rating,1)) ?>)</span>
                        </div>

                        <?php if (!empty($p['precio'])): ?>
                            <span class="precio"><?= htmlspecialchars($p['precio']) ?></span>
                        <?php endif; ?>

                        <?php if (!empty($p['descripcion'])): ?>
                            <p><?= nl2br(htmlspecialchars($p['descripcion'])) ?></p>
                        <?php endif; ?>

                        <small>
                            <?php
                                $autor = trim(($p['nombre'] ?? '') . ' ' . ($p['apellido'] ?? ''));
                                $autor = $autor !== '' ? $autor : 'Autor desconocido';
                                $fecha = $p['fecha'] ?? '';
                                echo htmlspecialchars("Por: {$autor}" . ($fecha ? " — {$fecha}" : ''));
                            ?>
                        </small>

                        <div class="hover-text">Ver detalles</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay publicaciones aún.</p>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/../secciones/footer.php'; ?>
</body>
</html>
