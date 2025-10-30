<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: sesion.php");
    exit;
}
$usuarioId = $_SESSION['id_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajería</title>
    <link rel="stylesheet" href="../assets/estilos/mensajeria.css">
</head>
<body>
    <div class="chat-container">
        <h2>Mensajería</h2>

        <div id="chat-box" class="chat-box"></div>

        <form id="form-mensaje">
            <input type="hidden" id="receptor_id" name="receptor_id" value="2"> <!-- Cambiar por el ID del receptor -->
            <textarea id="mensaje" name="mensaje" placeholder="Escribe un mensaje..." required></textarea>
            <button type="submit">Enviar</button>
        </form>
    </div>

<script>
const form = document.getElementById('form-mensaje');
const chatBox = document.getElementById('chat-box');
const receptorId = document.getElementById('receptor_id').value;

// Enviar mensaje por AJAX
form.addEventListener('submit', e => {
    e.preventDefault();
    const data = new FormData(form);

    fetch('../controlador/mensajeriaControler.php', {
        method: 'POST',
        body: data
    }).then(() => {
        form.mensaje.value = '';
        cargarMensajes();
    });
});

// Cargar mensajes del chat
function cargarMensajes() {
    fetch(`../controlador/mensajeriaControler.php?receptor_id=${receptorId}`)
        .then(res => res.json())
        .then(mensajes => {
            chatBox.innerHTML = '';
            mensajes.forEach(msg => {
                const div = document.createElement('div');
                div.className = (msg.emisor_id == <?= $usuarioId ?>) ? 'msg propio' : 'msg ajeno';
                div.textContent = `${msg.nombre_emisor}: ${msg.mensaje}`;
                chatBox.appendChild(div);
            });
            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

// Recargar cada 3 segundos
setInterval(cargarMensajes, 3000);
cargarMensajes();
</script>
</body>
</html>
