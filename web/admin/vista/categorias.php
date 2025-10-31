<?php if (isset($_GET['creada'])): ?>
    <div class="alert alert-success">Categoría creada correctamente</div>
<?php endif; ?>

<?php if (isset($_GET['eliminada'])): ?>
    <div class="alert alert-success">Categoría eliminada correctamente</div>
<?php endif; ?>

<?php if (isset($mensaje_error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($mensaje_error) ?></div>
<?php endif; ?>

<!-- Formulario crear categoría -->
<div class="form-card">
    <h3>Crear Nueva Categoría</h3>
    <form method="post" class="admin-form">
        <input type="hidden" name="crear_categoria" value="1">
        
        <div class="form-row">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required placeholder="Ej: Electricidad">
            </div>
            <div class="form-group">
                <label>Icono (emoji):</label>
                <input type="text" name="icono" value="📋" maxlength="2" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" rows="3" placeholder="Descripción de la categoría"></textarea>
        </div>
        
        <button type="submit" class="btn-primary">Crear Categoría</button>
    </form>
</div>

<!-- Lista de categorías -->
<div class="categorias-grid">
    <?php foreach ($categorias as $cat): ?>
        <div class="categoria-card">
            <div class="categoria-icon"><?= htmlspecialchars($cat['icono']) ?></div>
            <div class="categoria-info">
                <h4><?= htmlspecialchars($cat['nombre']) ?></h4>
                <?php if (!empty($cat['descripcion'])): ?>
                    <p><?= htmlspecialchars($cat['descripcion']) ?></p>
                <?php endif; ?>
            </div>
            <a href="?seccion=categorias&eliminar_cat=<?= $cat['id'] ?>" 
               class="btn-delete-cat" 
               onclick="return confirm('¿Eliminar esta categoría?')" title="Eliminar">🗑️</a>
        </div>
    <?php endforeach; ?>
</div>
