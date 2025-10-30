<?php
// vista/app/publicacion.php
require_once __DIR__ . "/../../controlador/publicacionControler.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($publicacion['titulo']); ?></title>
    <link rel="stylesheet" href="../estilos/inicio.css">
</head>
<body>
    <main class="detalle-publicacion">
        <h2><?php echo htmlspecialchars($publicacion['titulo']); ?></h2>

        <?php if (!empty($publicacion['imagen'])): ?>
            <img src="../../<?php echo htmlspecialchars($publicacion['imagen']); ?>" alt="Imagen">
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($publicacion['descripcion'])); ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($publicacion['precio'], 2); ?></p>

        <p>
            <small>
                Publicado por:
                <?php echo htmlspecialchars($publicacion['nombre']." ".$publicacion['apellido']); ?>
                el <?php echo $publicacion['fecha']; ?>
            </small>
        </p>

        <p><a href="inicio.php">⬅ Volver al inicio</a></p>
    </main>
</body>
</html>
