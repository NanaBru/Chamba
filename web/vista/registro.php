<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
    <link rel="stylesheet" href="/chamba/web/vista/assets/estilos/registro.css">
    <link rel="stylesheet" href="/chamba/web/vista/assets/estilos/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
    <title>Registro</title>
</head>
<body>
    
    <?php include __DIR__ . '/secciones/nav.php'; ?>

     <section class="registro-seccion">
        <form action="/chamba/web/router.php?page=registro" method="post">
           
            <h2>Registro</h2>

            <!-- Campo de cédula (nuevo) -->
            <div class="campo-cedula">
                <input type="text" name="cedula" id="cedula" maxlength="20" 
                       placeholder="Cédula (opcional)" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       onblur="buscarCedula()">
                <span id="cedula-status" class="cedula-status"></span>
            </div>

            <div class="modal-nombre">
                <input type="text" name="nombre" id="nombre" maxlength="30" minlength="3" title="Nombre de usuario"
                    placeholder="Nombre" onkeydown="noEspacios(event), soloLetras(event)" required>

                <input type="text" name="apellido" id="apellido" maxlength="30" minlength="3"
                    title="Apellido de usuario" placeholder="Apellido" onkeydown="noEspacios(event), soloLetras(event)" required>
            </div>

            <div class="modal-nombre">
                <input type="text" name="telefono" id="telefono" maxlength="9" minlength="9"
                    title="Telefono de usuario" placeholder="Telefono" onkeydown="noEspacios(event)"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>

                <input type="number" name="edad" id="edad" min="18" max="99" maxlength="2" minlength="2"
                    title="edad de usuario" placeholder="Edad" onkeydown="noEspacios(event)"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
            </div>

            <input class="input11" type="email" name="email" id="email" placeholder="Ingrese un email"
                title="Ingrese un email válido" onkeydown="noEspacios(event)" required>

            <input class="input11" type="password" name="password" id="passwordA" maxlength="15" minlength="6"
                placeholder="Escriba una contraseña" title="Escribe contraseña" onkeydown="noEspacios(event)"
                onblur="verificarPass()" required>

            <input class="input11" type="password" name="password_confirm" id="passwordB" maxlength="15" minlength="6"
                placeholder="Verifica su contraseña" title="Verifica contraseña" onkeydown="noEspacios(event)"
                onblur="verificarPass()" required>

            <?php if (!empty($_GET['error'])): ?>
                <div class="mensaje-error" style="color:red; margin-bottom:10px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
                
            <button type="submit" id="enviar">Registrarse</button>
            <a style="margin-top: 10px;" href="/chamba/web/router.php?page=sesion">¿Ya tienes una cuenta?</a>
        </form>
    </section>

    <script src="../vista/assets/js/script.js"></script>
    
    <script>
let cedulaDebounce;

document.getElementById('cedula').addEventListener('input', function() {
    clearTimeout(cedulaDebounce);
    const cedula = this.value.trim();
    const status = document.getElementById('cedula-status');
    
    // Si borran la cédula, limpiar
    if (cedula.length === 0) {
        status.textContent = '';
        desbloquearCampos();
        return;
    }
    
    // Validar que tenga 7 u 8 dígitos
    if (cedula.length < 7 || cedula.length > 8) {
        status.textContent = '';
        return;
    }
    
    // Mostrar indicador de búsqueda
    status.textContent = '🔍';
    status.style.color = 'blue';
    
    // Debounce: esperar 400ms después de que termine de escribir
    cedulaDebounce = setTimeout(() => {
        fetch('/chamba/web/api/buscar_cedula.php?cedula=' + cedula)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Encontrado: autocompletar y bloquear campos
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('apellido').value = data.apellido;
                    status.textContent = '✅';
                    status.style.color = 'green';
                    bloquearCampos();
                } else {
                    // No encontrado: permitir escritura manual
                    status.textContent = '❌';
                    status.style.color = 'red';
                    desbloquearCampos();
                }
            })
            .catch(error => {
                // Error de red o servidor
                status.textContent = '⚠️';
                status.style.color = 'orange';
                desbloquearCampos();
                console.error('Error:', error);
            });
    }, 400);
});

function bloquearCampos() {
    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    
    nombre.setAttribute('readonly', true);
    apellido.setAttribute('readonly', true);
    nombre.style.backgroundColor = '#f0f0f0';
    apellido.style.backgroundColor = '#f0f0f0';
    nombre.style.cursor = 'not-allowed';
    apellido.style.cursor = 'not-allowed';
}

function desbloquearCampos() {
    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    
    nombre.removeAttribute('readonly');
    apellido.removeAttribute('readonly');
    nombre.style.backgroundColor = '';
    apellido.style.backgroundColor = '';
    nombre.style.cursor = '';
    apellido.style.cursor = '';
}
</script>



    
</body>
</html>
