<?php if (isset($_GET['eliminado'])): ?>
    <div class="alert alert-success">Mensaje eliminado correctamente</div>
<?php endif; ?>

<div class="table-card">
    <h3>Mensajes Recientes (<?= count($mensajes) ?>)</h3>
    <p class="info-text">Mostrando los últimos 500 mensajes del sistema</p>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>De</th>
                    <th>Para</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensajes as $msg): ?>
                    <tr>
                        <td><?= $msg['id'] ?></td>
                        <td><?= htmlspecialchars($msg['emisor_nombre'] . ' ' . $msg['emisor_apellido']) ?></td>
                        <td><?= htmlspecialchars($msg['receptor_nombre'] . ' ' . $msg['receptor_apellido']) ?></td>
                        <td class="mensaje-preview">
                            <?= htmlspecialchars(mb_substr($msg['mensaje'], 0, 80)) ?><?= strlen($msg['mensaje']) > 80 ? '...' : '' ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($msg['fecha_envio'])) ?></td>
                        <td>
                            <a href="?seccion=mensajes&eliminar_msg=<?= $msg['id'] ?>" 
                               class="btn-delete" 
                               onclick="return confirm('¿Eliminar este mensaje?')" title="Eliminar">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
