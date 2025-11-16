<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/stylesNav.css">
<link rel="stylesheet" href="/chamba/web/vista/assets/estilos/facturas.css">
<link rel="shortcut icon" href="/chamba/web/vista/assets/img/logopng.png" type="image/x-icon">
<title>Mis Facturas - Chamba</title>
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

<main class="facturas-main">
    <div class="facturas-container">
        <h1>💰 Facturas Pendientes</h1>
        
        <?php if (empty($facturasPendientes)): ?>
            <div class="sin-facturas">
                <p>No tienes facturas pendientes</p>
                <a href="/chamba/web/router.php?page=chat" class="btn-volver">Volver al chat</a>
            </div>
        <?php else: ?>
            <div class="facturas-lista">
                <?php foreach ($facturasPendientes as $factura): ?>
                    <div class="factura-card">
                        <div class="factura-header">
                            <div class="proveedor-info">
                                <h3><?= htmlspecialchars($factura['nombre'] . ' ' . $factura['apellido']) ?></h3>
                                <?php if (!empty($factura['servicio'])): ?>
                                    <p class="servicio">Servicio: <?= htmlspecialchars($factura['servicio']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="factura-monto">
                                <span class="monto">$<?= number_format($factura['monto'], 2) ?></span>
                            </div>
                        </div>

                        <div class="factura-descripcion">
                            <h4>Descripción del trabajo:</h4>
                            <p><?= nl2br(htmlspecialchars($factura['descripcion'])) ?></p>
                        </div>

                        <?php if (!empty($factura['fotos'])): ?>
                            <?php $fotos = json_decode($factura['fotos'], true); ?>
                            <?php if (!empty($fotos)): ?>
                                <div class="factura-fotos">
                                    <h4>Fotos del trabajo:</h4>
                                    <div class="fotos-grid">
                                        <?php foreach ($fotos as $foto): ?>
                                            <img src="/chamba/web/datos/facturas/<?= htmlspecialchars($foto) ?>" 
                                                 alt="Foto del trabajo" 
                                                 onclick="abrirImagen(this.src)">
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="factura-fecha">
                            <small>Enviada el <?= date('d/m/Y H:i', strtotime($factura['fecha_creacion'])) ?></small>
                        </div>

                        <div class="factura-acciones">
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="factura_id" value="<?= $factura['id'] ?>">
                                <button type="button" onclick="abrirModalPago(<?= $factura['id'] ?>, <?= $factura['monto'] ?>)" 
        class="btn-pagar">
    💳 Pagar con Tarjeta
</button>

                            </form>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="factura_id" value="<?= $factura['id'] ?>">
                                <button type="submit" name="rechazar_factura" class="btn-rechazar" 
                                        onclick="return confirm('¿Estás seguro de rechazar esta factura?')">
                                    ❌ Rechazar
                                </button>
                            </form>
                            <a href="/chamba/web/router.php?page=chat&contacto=<?= $factura['proveedor_id'] ?>" 
                               class="btn-contactar">
                                💬 Contactar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<!-- Modal de pago simulado -->
<div id="modalPago" class="modal-pago">
    <div class="modal-pago-contenido">
        <span class="cerrar-pago" onclick="cerrarModalPago()">&times;</span>
        <h2>💳 Procesar Pago</h2>
        
        <div class="resumen-pago">
            <p><strong>Monto a pagar:</strong></p>
            <p class="monto-pago" id="montoPago">$0.00</p>
        </div>

        <form id="formPago" method="post">
            <input type="hidden" name="pagar_factura" value="1">
            <input type="hidden" name="factura_id" id="facturaIdPago">
            
            <div class="form-group-pago">
                <label>Número de tarjeta</label>
                <input type="text" id="numeroTarjeta" placeholder="1234 5678 9012 3456" 
                       maxlength="19" required pattern="\d{4}\s\d{4}\s\d{4}\s\d{4}">
            </div>

            <div class="form-row">
                <div class="form-group-pago">
                    <label>Fecha de vencimiento</label>
                    <input type="text" id="fechaVenc" placeholder="MM/AA" 
                           maxlength="5" required pattern="\d{2}/\d{2}">
                </div>
                <div class="form-group-pago">
                    <label>CVV</label>
                    <input type="text" id="cvv" placeholder="123" 
                           maxlength="3" required pattern="\d{3}">
                </div>
            </div>

            <div class="form-group-pago">
                <label>Nombre del titular</label>
                <input type="text" id="nombreTitular" placeholder="JUAN PEREZ" required>
            </div>

            <div class="logos-tarjetas">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="Amex">
            </div>

            <button type="submit" class="btn-procesar-pago">
                🔒 Procesar Pago Seguro
            </button>
        </form>

        <p class="texto-seguro">
            🔐 Tu información está protegida con encriptación SSL de 256 bits
        </p>
    </div>
</div>

<!-- Modal para ver imagen completa -->
<div id="modalImagen" class="modal-imagen" onclick="cerrarModalImagen()">
    <span class="cerrar">&times;</span>
    <img class="modal-imagen-contenido" id="imagenAmpliada">
</div>


<script>
let facturaActualId = 0;
let montoActual = 0;

function abrirModalPago(facturaId, monto) {
    facturaActualId = facturaId;
    montoActual = monto;
    document.getElementById('modalPago').style.display = 'flex';
    document.getElementById('facturaIdPago').value = facturaId;
    document.getElementById('montoPago').textContent = '$' + parseFloat(monto).toFixed(2);
}

function cerrarModalPago() {
    document.getElementById('modalPago').style.display = 'none';
    document.getElementById('formPago').reset();
}

// Formatear número de tarjeta
document.getElementById('numeroTarjeta').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Formatear fecha
document.getElementById('fechaVenc').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        e.target.value = value.slice(0,2) + '/' + value.slice(2,4);
    } else {
        e.target.value = value;
    }
});

// Solo números en CVV
document.getElementById('cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});

// Validación y simulación de pago
document.getElementById('formPago').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simular procesamiento
    const btnSubmit = this.querySelector('.btn-procesar-pago');
    btnSubmit.disabled = true;
    btnSubmit.textContent = '⏳ Procesando pago...';
    
    setTimeout(() => {
        // Simular éxito (en producción real vendría de la pasarela)
        alert('✅ Pago procesado exitosamente\nMonto: $' + montoActual.toFixed(2));
        this.submit(); // Enviar el formulario real
    }, 2000);
});
</script>

<script>
function abrirImagen(src) {
    document.getElementById('modalImagen').style.display = 'flex';
    document.getElementById('imagenAmpliada').src = src;
}

function cerrarModalImagen() {
    document.getElementById('modalImagen').style.display = 'none';
}
</script>

<script src="/chamba/web/vista/assets/js/script.js"></script>
</body>
</html>
