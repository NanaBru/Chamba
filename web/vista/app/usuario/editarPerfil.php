<?php
// Variables: $usuario, $misPublicaciones, $mensaje_error, $mensaje_exito
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/editarPerfil.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Editar Perfil</title>
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
            <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>   
</nav>

<main class="editar-perfil-main">
    <h1 class="page-title">Editar Perfil</h1>

    <?php if(!empty($mensaje_error)): ?>
        <div class="mensaje error"><?= htmlspecialchars($mensaje_error) ?></div>
    <?php endif; ?>
    <?php if(!empty($mensaje_exito)): ?>
        <div class="mensaje exito"><?= htmlspecialchars($mensaje_exito) ?></div>
    <?php endif; ?>

    <form method="post" class="form-editar">
        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
            </div>

            <div class="form-group">
                <label for="edad">Edad</label>
                <input type="number" id="edad" name="edad" value="<?= htmlspecialchars($usuario['edad']) ?>" required min="18" max="99">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" maxlength="9" minlength="9" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>

            <div class="form-group full-width">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="form-group full-width">
                <label for="descripcion">Descripción (opcional) <span id="contador">600 caracteres restantes</span></label>
                <textarea id="descripcion" name="descripcion" rows="4" maxlength="600" placeholder="Cuéntanos sobre tu experiencia, servicios que ofreces, etc." oninput="actualizarContador()"><?= htmlspecialchars($usuario['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="form-group full-width">
                <label for="password">Nueva Contraseña (opcional)</label>
                <input type="password" id="password" name="password" placeholder="Dejar en blanco si no desea cambiar">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-actualizar">Actualizar Perfil</button>
            <a href="/chamba/web/router.php?page=perfil" class="btn-cancelar">Cancelar</a>
        </div>
    </form>

    <!-- Mis Publicaciones -->
    <section class="mis-publicaciones-editar">
        <h2>Mis Publicaciones</h2>
        
        <?php if (!empty($misPublicaciones)): ?>
            <div class="publicaciones-grid">
                <?php foreach ($misPublicaciones as $pub): ?>
                    <div class="pub-card">
                        <?php if (!empty($pub['imagen'])): ?>
                            <img src="/chamba/web/datos/publicasiones/<?= htmlspecialchars($pub['imagen']) ?>" alt="<?= htmlspecialchars($pub['titulo']) ?>">
                        <?php endif; ?>
                        
                        <div class="pub-info">
                            <h3><?= htmlspecialchars($pub['titulo']) ?></h3>
                            <p class="pub-precio">$<?= number_format($pub['precio'], 2) ?></p>
                            <p class="pub-descripcion"><?= htmlspecialchars(mb_substr($pub['descripcion'], 0, 80)) ?>...</p>
                        </div>
                        
                        <div class="pub-acciones">
                            <a href="/chamba/web/router.php?page=publicacion&id=<?= $pub['id'] ?>" class="btn-ver">Ver</a>
                            <a href="/chamba/web/router.php?page=editar-perfil&eliminar=<?= $pub['id'] ?>" 
                               class="btn-eliminar" 
                               onclick="return confirm('¿Estás seguro de eliminar esta publicación?')">Eliminar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="sin-publicaciones">Aún no tienes publicaciones.</p>
        <?php endif; ?>
    </section>
</main>

<script>
function actualizarContador() {
    const textarea = document.getElementById('descripcion');
    const contador = document.getElementById('contador');
    const restantes = 600 - textarea.value.length;
    contador.textContent = restantes + ' caracteres restantes';
    contador.style.color = restantes < 50 ? '#c33' : '#666';
}
window.addEventListener('DOMContentLoaded', actualizarContador);
</script>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
