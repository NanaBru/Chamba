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
                    <a href="/chamba/web/router.php?page=perfil&id=<?= $contacto_id ?>" class="contacto-info">
    <div class="contacto-avatar">
        <?php if (!empty($contacto['foto_perfil'])): ?>
            <img src="/chamba/web/datos/usuarios/<?= htmlspecialchars($contacto['foto_perfil']) ?>" alt="">
        <?php else: ?>
            <span><?= strtoupper(substr($contacto['nombre'], 0, 1)) ?></span>
        <?php endif; ?>
    </div>
    <h2><?= htmlspecialchars($contacto['nombre'] . ' ' . $contacto['apellido']) ?></h2>
</a>

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
                <?php if ($esProveedor): ?>
    <button onclick="mostrarFactura()" class="btn-enviar-factura">💳 Enviar Factura</button>
<?php endif; ?>

<?php if (!empty($facturasPendientes)): ?>
    <a href="/chamba/web/router.php?page=mis-facturas" class="btn-facturas">
        💰 Facturas (<?= count($facturasPendientes) ?>)
    </a>
<?php endif; ?>

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

    <!-- Modal para enviar factura -->
<?php if ($esProveedor && $contacto): ?>
<div id="modalFactura" class="modal-solicitud">
    <div class="modal-contenido">
        <h3>Enviar Factura de Servicio</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="enviar_factura" value="1">
            
            <!-- Seleccionar publicación (opcional) -->
            <?php if (!empty($misPublicaciones)): ?>
            <div class="form-group">
                <label>Servicio relacionado (opcional):</label>
                <select name="publicacion_id_factura" class="form-control">
                    <option value="">Sin servicio específico</option>
                    <?php foreach ($misPublicaciones as $pub): ?>
                        <option value="<?= $pub['id'] ?>"><?= htmlspecialchars($pub['titulo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <!-- Descripción del trabajo -->
            <div class="form-group">
                <label>Descripción del trabajo realizado: *</label>
                <textarea name="descripcion_factura" required rows="4" class="form-control" 
                          placeholder="Describe el trabajo que realizaste..."></textarea>
            </div>
            
            <!-- Monto -->
            <div class="form-group">
                <label>Monto a cobrar: *</label>
                <input type="number" name="monto_factura" required step="0.01" min="0.01" 
                       class="form-control" placeholder="0.00">
            </div>
            
            <!-- Fotos del trabajo -->
            <div class="form-group">
                <label>Fotos del trabajo (máx. 5):</label>
                <input type="file" name="fotos_factura[]" accept="image/*" multiple 
                       class="form-control" id="fotosFactura" onchange="previsualizarFotos(this)">
                <div id="preview-fotos" class="preview-fotos"></div>
            </div>
            
            <div class="modal-botones">
                <button type="submit" class="btn-enviar-modal">Enviar Factura</button>
                <button type="button" onclick="cerrarModalFactura()" class="btn-cancelar-modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

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

// Modal de solicitud de reseña
function mostrarSolicitudResena() {
    document.getElementById('modalSolicitud').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalSolicitud').style.display = 'none';
}

// Modal de factura
function mostrarFactura() {
    document.getElementById('modalFactura').style.display = 'flex';
}

function cerrarModalFactura() {
    document.getElementById('modalFactura').style.display = 'none';
    document.getElementById('preview-fotos').innerHTML = '';
}

// Previsualizar fotos
function previsualizarFotos(input) {
    const preview = document.getElementById('preview-fotos');
    preview.innerHTML = '';
    
    if (input.files) {
        const maxFiles = Math.min(input.files.length, 5);
        
        for (let i = 0; i < maxFiles; i++) {
            const file = input.files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img';
                preview.appendChild(img);
            }
            
            reader.readAsDataURL(file);
        }
    }
}

// Cerrar modales al hacer clic fuera
window.onclick = function(event) {
    const modalSolicitud = document.getElementById('modalSolicitud');
    const modalFactura = document.getElementById('modalFactura');
    
    if (event.target == modalSolicitud) {
        cerrarModal();
    }
    if (event.target == modalFactura) {
        cerrarModalFactura();
    }
}
</script>


<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
