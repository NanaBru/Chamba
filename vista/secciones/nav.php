<nav id="menu">
    <div class="logoDIV1">
        <img class="logo" src="../vista/img/logopng.png" alt="logo">
        <a href="index.php">
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
            <li><a href="index.php">Inicio</a></li>
            <li><a href="#" onclick="mostrarNotificacion()" >Sobre</a></li>
            <li><a href="#" onclick="mostrarNotificacion()" >Contacto</a></li>
            <li><a href="registro.php">Registrarse</a></li>
            <li><a href="sesion.php">Iniciar Sesión</a></li>
        </ul>
    </div>
</nav>

<script src="../js/script.js"></script>