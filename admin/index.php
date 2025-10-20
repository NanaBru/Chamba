<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="../vista/estilos/stylesNav.css">
    <link rel="stylesheet" href="../vista/estilos/iniciarsesion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="shortcut icon" href="img/logopng.png" type="image/x-icon">
    <title>Iniciar Sesión</title>
</head>

<body>


    <section class="registro-seccion">
        <form action="../controlador/" method="post" id="formulario">
            <h2>Administrador <br> Chamba</h2>

            <input class="input11" type="email" name="email" id="email" placeholder="Ingrese un email"
                title="Ingrese un email válido" onkeydown="noEspacios(event)" required>


            <input class="input11" type="password" name="password" id="password" maxlength="15" minlength="6"
                placeholder="Ingrese contraseña" title="Escribe contraseña" onkeydown="noEspacios(event)"
                onblur="verificarPass()" required>

             <?php if (!empty($_GET['error'])): ?>
                <div class="mensaje-error" style="color:red; margin-bottom:10px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <button type="submit" id="enviar">Iniciar sesión</button>
            <a style="margin-top: 10px;" href="../vista/registro.php">¿No tienes una cuenta?</a>
        </form>
    </section>

    <script src="../vista/js/script.js"></script>

</body>
</html>