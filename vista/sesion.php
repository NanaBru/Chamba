<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/stylesNav.css">
    <link rel="stylesheet" href="estilos/iniciarsesion.css">
    <link rel="stylesheet" href="estilos/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="shortcut icon" href="img/logopng.png" type="image/x-icon">
    <title>Iniciar Sesión</title>
</head>

<body>
<?php include __DIR__ . '/secciones/nav.php'; ?>

    <section class="registro-seccion">
        <form action="../controlador/sesionControler.php" method="post" id="formulario">
            <h2>Iniciar sesión</h2>

            <input class="input11" type="email" name="email" id="email" placeholder="Ingrese un email"
                title="Ingrese un email válido" onkeydown="noEspacios(event)" required>


            <input class="input11" type="password" name="password" id="password" maxlength="15" minlength="6"
                placeholder="Ingrese contraseña" title="Escribe contraseña" onkeydown="noEspacios(event)"
                onblur="verificarPass()" required>




            <div id="mensaje"></div>

            <button type="submit" id="enviar">Iniciar sesión</button>
        </form>
    </section>

    <script src="../vista/js/script.js"></script>


</body>

</html>