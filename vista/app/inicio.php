<?php
// Invocar al controlador que carga los datos
require_once __DIR__ . "/../../controlador/inicioControler.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Chamba</title>
    <link rel="stylesheet" href="../../vista/estilos/inicio.css">
    <link rel="stylesheet" href="../../vista/estilos/stylesNav.css">
    <link rel="stylesheet" href="../../vista/estilos/footer.css">
    <link rel="shortcut icon" href="../../vista/img/logopng.png" type="image/x-icon">
</head>

<body>

    <nav id="menu">
        <div class="logoDIV1">
            <img class="logo" src="../../vista/img/logopng.png" alt="logo">
            <a href="inicio.php">
                <h1 class="logoNombre">Chamba</h1>
            </a>
        </div>
        <div class="LogoDIV2">
            <div class="hamburguesa" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
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
            <?php if (!empty($publicaciones)): ?>
            <?php foreach ($publicaciones as $pub): ?>
                <a href="publicacion.php<?php echo $pub['id']; ?>" class="link-card">
                <article class="card">
                        <?php if (!empty($pub['imagen'])): ?>
                            <img src="../../<?php echo htmlspecialchars($pub['imagen']); ?>" alt="Imagen">
                        <?php endif; ?>
                        <h4><?php echo htmlspecialchars($pub['titulo']); ?></h4>
                        <span class="precio">
                            <?php echo $pub['precio'] !== null ? '$ '.number_format($pub['precio'],2) : ''; ?>
                        </span>
                        <p><?php echo nl2br(htmlspecialchars($pub['descripcion'])); ?></p>
                        <small>
                            Publicado por:
                            <?php echo htmlspecialchars($pub['nombre'].' '.$pub['apellido']); ?>
                            el <?php echo $pub['fecha']; ?>
                        </small>
                    </article>
                </a>
            <?php endforeach; ?>
            <?php else: ?>
                <p>No hay publicaciones aún.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/../secciones/footer.php'; ?>

    <script src="../js/script.js"></script>

</body>
</html>
