<?php
// web/vista/sesion.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['email'])) {
    header('Location: /chamba/web/router.php?page=inicio');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
    <link rel="stylesheet" href="/chamba/web/vista/assets/estilos/iniciarsesion.css">
    <link rel="stylesheet" href="/chamba/web/vista/assets/estilos/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
    <title>Iniciar Sesión</title>
</head>
<body>
    <?php include __DIR__ . '/secciones/nav.php'; ?>
    <section class="registro-seccion">
        <form action="/chamba/web/controlador/UsuarioControler.php" method="post">
            <input type="hidden" name="accion" value="login">
            <h2>Iniciar sesión</h2>

            <input class="input11" type="email" name="email" id="email" placeholder="Ingrese un email"
                   title="Ingrese un email válido" required>

            <input class="input11" type="password" name="password" id="password" maxlength="64" minlength="6"
                   placeholder="Ingrese contraseña" title="Escribe contraseña" required>

            <?php if (!empty($_GET['error'])): ?>
                <div class="mensaje-error" style="color:red; margin-bottom:10px;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_GET['mensaje'])): ?>
                <div class="mensaje-info" style="color:green; margin-bottom:10px;">
                    <?php echo htmlspecialchars($_GET['mensaje']); ?>
                </div>
            <?php endif; ?>

            <button type="submit" id="enviar">Iniciar sesión</button>
            <a style="margin-top: 10px;" href="/chamba/web/router.php?page=registro">¿No tienes una cuenta?</a>
        </form>
    </section>
    <script src="../vista/assets/js/script.js"></script>
</body>
</html>
