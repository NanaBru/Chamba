<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vista/estilos/stylesNav.css">
    <link rel="stylesheet" href="../vista/estilos/registro.css">
    <link rel="stylesheet" href="../vista/estilos/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="shortcut icon" href="../vista/img/logopng.png" type="image/x-icon">
    <title>Registro</title>
</head>
<body>
    
    <?php include __DIR__ . '/secciones/nav.php'; ?>

     <section class="registro-seccion">
        <form action="../controlador/registroControler.php" method="post" id="formulario">
            <h2>Registro</h2>

            <div class="modal-nombre">
                <input type="text" name="nombre" id="nombre" maxlength="30" minlength="3" title="Nombre de usuario"
                    placeholder="Nombre" onkeydown="noEspacios(event), soloLetras(event)" required>

                <input type="text" name="apellido" id="apellido" maxlength="30" minlength="3"
                    title="Apellido de usuario" placeholder="Apellido" onkeydown="noEspacios(event)" required>

            </div>

            <div class="moodal-cedula">

                <input class="cte" type="text" name="telefono" id="telefono" maxlength="9" minlength="9"
                    title="Telefono de usuario" placeholder="Telefono" onkeydown="noEspacios(event)"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>

                <input class="cte" type="text" name="edad" id="edad" min="18" max="99" maxlength="2" minlength="2"
                    title="edad de usuario" placeholder="Edad" onkeydown="noEspacios(event)"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>

            </div>

            <input class="input11" type="email" name="email" id="email" placeholder="Ingrese un email"
                title="Ingrese un email válido" onkeydown="noEspacios(event)" required>

            <input class="input11" type="password" name="passwordA" id="passwordA" maxlength="15" minlength="6"
                placeholder="Escriba una contraseña" title="Escribe contraseña" onkeydown="noEspacios(event)"
                onblur="verificarPass()" required>

            <input class="input11" type="password" name="passwordB" id="passwordB" maxlength="15" minlength="6"
                placeholder="Verifica su contraseña" title="Verifica contraseña" onkeydown="noEspacios(event)"
                onblur="verificarPass()" required>

            <div id="mensaje"></div>

            <button type="submit" id="enviar">Registrarse</button>
        </form>
    </section>


    <script src="../vista/js/script.js"></script>
    
</body>
</html>