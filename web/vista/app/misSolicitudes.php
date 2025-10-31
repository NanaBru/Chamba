<?php
// Variables: $solicitudes, $mensaje_error
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/misSolicitudes.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Mis Solicitudes - Chamba</title>
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
            <li><a href="/chamba/web/router.php?page=chat">Chat</a></li>
            <li><a href="/chamba/web/router.php?page=perfil">Perfil</a></li>
            <li><a href="/chamba/web/vista/app/usuario/logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>   
</nav>

<main class="solicitudes-main">
    <h1>Solicitudes de Reseña</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="mensaje exito">✓ Reseña enviada correctamente</div>
    <?php endif; ?>

    <?php if (isset($mensaje_error)): ?>
        <div class="mensaje error"><?= htmlspecialchars($mensaje_error) ?></div>
    <?php endif; ?>

    <?php if (empty($solicitudes)): ?>
        <div class="sin-solicitudes">
            <p>No tienes solicitudes de reseña pendientes</p>
            <a href="/chamba/web/router.php?page=chat" class="btn-volver">Volver al chat</a>
        </div>
    <?php else: ?>
        <div class="solicitudes-grid">
            <?php foreach ($solicitudes as $sol): ?>
                <div class="solicitud-card">
                    <div class="solicitud-header">
                        <?php if (!empty($sol['imagen'])): ?>
                            <img src="/chamba/web/datos/publicasiones/<?= htmlspecialchars($sol['imagen']) ?>" alt="<?= htmlspecialchars($sol['titulo']) ?>">
                        <?php endif; ?>
                        <div class="solicitud-info">
                            <h3><?= htmlspecialchars($sol['titulo']) ?></h3>
                            <p>Solicitado por: <strong><?= htmlspecialchars($sol['nombre'] . ' ' . $sol['apellido']) ?></strong></p>
                            <small><?= date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])) ?></small>
                        </div>
                    </div>

                    <form method="post" class="form-calificar" onsubmit="return validarEstrellas(this)">
                        <input type="hidden" name="calificar" value="1">
                        <input type="hidden" name="publicacion_id" value="<?= $sol['publicacion_id'] ?>">
                        <input type="hidden" name="estrellas" id="estrellas-<?= $sol['id'] ?>" value="0">

                        <label>Calificación:</label>
                        <div class="rating-selector" data-solicitud="<?= $sol['id'] ?>">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star" data-rating="<?= $i ?>" onclick="seleccionarEstrella(<?= $sol['id'] ?>, <?= $i ?>)">★</span>
                            <?php endfor; ?>
                        </div>

                        <label for="comentario-<?= $sol['id'] ?>">Comentario:</label>
                        <textarea name="comentario" id="comentario-<?= $sol['id'] ?>" rows="4" placeholder="Escribe tu experiencia con este servicio..."></textarea>

                        <button type="submit" class="btn-enviar-resena">Enviar Reseña</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
function seleccionarEstrella(solicitudId, rating) {
    const input = document.getElementById('estrellas-' + solicitudId);
    input.value = rating;
    
    const container = document.querySelector(`.rating-selector[data-solicitud="${solicitudId}"]`);
    const stars = container.querySelectorAll('.star');
    
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('activa');
        } else {
            star.classList.remove('activa');
        }
    });
}

function validarEstrellas(form) {
    const estrellas = form.querySelector('input[name="estrellas"]').value;
    if (estrellas == 0) {
        alert('Por favor selecciona una calificación de 1 a 5 estrellas');
        return false;
    }
    return true;
}
</script>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
