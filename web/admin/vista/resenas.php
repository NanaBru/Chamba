<?php if (isset($_GET['eliminado'])): ?>
    <div class="alert alert-success">Reseña eliminada correctamente</div>
<?php endif; ?>

<div class="table-card">
    <h3>Reseñas Publicadas (<?= count($resenas) ?>)</h3>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Publicación</th>
                    <th>Estrellas</th>
                    <th>Comentario</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resenas as $res): ?>
                    <tr>
                        <td><?= $res['id'] ?></td>
                        <td><?= htmlspecialchars($res['nombre'] . ' ' . $res['apellido']) ?></td>
                        <td><?= htmlspecialchars($res['publicacion_titulo']) ?></td>
                        <td>
                            <div class="stars-display">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="<?= $i <= $res['estrellas'] ? 'star-filled' : 'star-empty' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td class="comentario-preview">
                            <?php if (!empty($res['comentario'])): ?>
                                <?= htmlspecialchars(mb_substr($res['comentario'], 0, 60)) ?><?= strlen($res['comentario']) > 60 ? '...' : '' ?>
                            <?php else: ?>
                                <span class="sin-comentario">Sin comentario</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($res['fecha'])) ?></td>
                        <td>
                            <a href="?seccion=resenas&eliminar_resena=<?= $res['id'] ?>" 
                               class="btn-delete" 
                               onclick="return confirm('¿Eliminar esta reseña?')" title="Eliminar">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
