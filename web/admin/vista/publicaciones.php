<?php if (isset($_GET['eliminado'])): ?>
    <div class="alert alert-success">Publicación eliminada correctamente</div>
<?php endif; ?>

<div class="table-card">
    <h3>📝 Lista de Publicaciones (<?= count($publicaciones) ?>)</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($publicaciones as $pub): ?>
                    <tr>
                        <td><?= $pub['id'] ?></td>
                        <td>
                            <?php if (!empty($pub['imagen'])): ?>
                                <img src="../datos/publicasiones/<?= htmlspecialchars($pub['imagen']) ?>" class="mini-img">
                            <?php else: ?>
                                <div class="mini-img-placeholder">📷</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($pub['titulo']) ?></strong>
                            <p class="desc-preview"><?= htmlspecialchars(mb_substr($pub['descripcion'], 0, 60)) ?>...</p>
                        </td>
                        <td><?= htmlspecialchars($pub['nombre'] . ' ' . $pub['apellido']) ?></td>
                        <td class="precio-cell">$<?= number_format($pub['precio'], 2) ?></td>
                        <td><?= date('d/m/Y', strtotime($pub['fecha'])) ?></td>
                        <td>
                            <a href="../router.php?page=publicacion&id=<?= $pub['id'] ?>" 
                               class="btn-view" target="_blank" title="Ver">👁️</a>
                            <a href="?seccion=publicaciones&eliminar_pub=<?= $pub['id'] ?>" 
                               class="btn-delete" 
                               onclick="return confirm('¿Eliminar esta publicación?')" title="Eliminar">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
