<?php
// Variables: $conversaciones, $mensajes, $contacto, $usuario_id, $misPublicaciones, $esProveedor, $solicitudesPendientes
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/chat.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Chat - Chamba</title>
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

<main class="chat-container">
    <!-- Lista de conversaciones -->
    <aside class="chat-sidebar <?= empty($contacto_id) ? 'visible' : '' ?>">
        <h2>Conversaciones</h2>
        <div class="conversaciones-lista">
            <?php if (empty($conversaciones)): ?>
                <p class="sin-conversaciones">No tienes conversaciones aún.</p>
            <?php else: ?>
                <?php foreach ($conversaciones as $conv): ?>
                    <a href="/chamba/web/router.php?page=chat&contacto=<?= $conv['contacto_id'] ?>" 
                       class="conversacion-item <?= $contacto_id == $conv['contacto_id'] ? 'activa' : '' ?>">
                        <div class="conv-avatar">
                            <?php if (!empty($conv['foto_perfil'])): ?>
                                <img src="/chamba/web/datos/usuarios/<?= htmlspecialchars($conv['foto_perfil']) ?>" alt="">
                            <?php else: ?>
                                <span><?= strtoupper(substr($conv['nombre'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="conv-info">
                            <h3><?= htmlspecialchars($conv['nombre'] . ' ' . $conv['apellido']) ?></h3>
                            <p><?= htmlspecialchars(mb_substr($conv['ultimo_mensaje'], 0, 40)) ?>...</p>
                        </div>
                        <?php if ($conv['no_leidos'] > 0): ?>
                            <span class="badge-no-leidos"><?= $conv['no_leidos'] ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Área de chat -->
    <section class="chat-area <?= !empty($contacto_id) ? 'visible' : '' ?>">
        <?php if ($contacto): ?>
            <!-- Header del chat -->
            <div class="chat-header">
                <a href="/chamba/web/router.php?page=chat" class="btn-volver-lista">← Volver</a>
                <div class="contacto-info">
                    <div class="contacto-avatar">
                        <?php if (!empty($contacto['foto_perfil'])): ?>
                            <img src="/chamba/web/datos/usuarios/<?= htmlspecialchars($contacto['foto_perfil']) ?>" alt="">
                        <?php else: ?>
                            <span><?= strtoupper(substr($contacto['nombre'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <h2><?= htmlspecialchars($contacto['nombre'] . ' ' . $contacto['apellido']) ?></h2>
                </div>
                
                <!-- Botones de acción -->
                <div class="chat-acciones">
                    <?php if (!empty($solicitudesPendientes)): ?>
                        <a href="/chamba/web/router.php?page=mis-solicitudes" class="btn-solicitudes">
                            📝 Solicitudes (<?= count($solicitudesPendientes) ?>)
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($esProveedor && !empty($misPublicaciones)): ?>
                        <button onclick="mostrarSolicitudResena()" class="btn-solicitar-resena">Solicitar reseña</button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mensajes -->
            <div class="mensajes-contenedor" id="mensajesContenedor">
                <?php if (empty($mensajes)): ?>
                    <p class="sin-mensajes">No hay mensajes aún. ¡Inicia la conversación!</p>
                <?php else: ?>
                    <?php foreach ($mensajes as $msg): ?>
                        <div class="mensaje <?= $msg['emisor_id'] == $usuario_id ? 'propio' : 'ajeno' ?>">
                            <div class="mensaje-contenido">
                                <p><?= nl2br(htmlspecialchars($msg['mensaje'])) ?></p>
                                <span class="mensaje-hora"><?= date('H:i', strtotime($msg['fecha_envio'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Formulario de envío -->
            <form method="post" class="chat-input-form">
                <input type="hidden" name="receptor_id" value="<?= $contacto_id ?>">
                <textarea name="mensaje" placeholder="Escribe un mensaje..." required rows="1"></textarea>
                <button type="submit">Enviar</button>
            </form>
        <?php else: ?>
            <div class="sin-seleccion">
                <p>Selecciona una conversación para comenzar</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<!-- Modal para seleccionar publicación -->
<?php if ($esProveedor && !empty($misPublicaciones) && $contacto): ?>
<div id="modalSolicitud" class="modal-solicitud">
    <div class="modal-contenido">
        <h3>Selecciona el servicio a calificar</h3>
        <form method="post">
            <input type="hidden" name="enviar_solicitud" value="1">
            <div class="lista-publicaciones">
                <?php foreach ($misPublicaciones as $pub): ?>
                    <label class="pub-option">
                        <input type="radio" name="publicacion_id" value="<?= $pub['id'] ?>" required>
                        <div class="pub-info-modal">
                            <?php if (!empty($pub['imagen'])): ?>
                                <img src="/chamba/web/datos/publicasiones/<?= htmlspecialchars($pub['imagen']) ?>" alt="">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($pub['titulo']) ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="modal-botones">
                <button type="submit" class="btn-enviar-modal">Enviar solicitud</button>
                <button type="button" onclick="cerrarModal()" class="btn-cancelar-modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
// Auto-scroll al último mensaje
const mensajesContainer = document.getElementById('mensajesContenedor');
if (mensajesContainer) {
    mensajesContainer.scrollTop = mensajesContainer.scrollHeight;
}

// Auto-expandir textarea
const textarea = document.querySelector('.chat-input-form textarea');
if (textarea) {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 150) + 'px';
    });
}

// Modal de solicitud
function mostrarSolicitudResena() {
    document.getElementById('modalSolicitud').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalSolicitud').style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modalSolicitud');
    if (event.target == modal) {
        cerrarModal();
    }
}
</script>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
