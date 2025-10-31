<?php if (isset($_GET['eliminado'])): ?>
    <div class="alert alert-success">Usuario eliminado correctamente</div>
<?php endif; ?>

<?php if (isset($mensaje_exito)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($mensaje_exito) ?></div>
<?php endif; ?>

<?php if (isset($mensaje_error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($mensaje_error) ?></div>
<?php endif; ?>

<!-- Formulario crear usuario -->
<div class="form-card">
    <h3>➕ Crear Nuevo Usuario</h3>
    <form method="post" class="admin-form">
        <input type="hidden" name="crear_usuario" value="1">
        <div class="form-row">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Edad:</label>
                <input type="number" name="edad" min="18" required>
            </div>
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono" maxlength="9" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Rol:</label>
            <select name="rol" required>
                <option value="usuario">Usuario</option>
                <option value="administrador">Administrador</option>
            </select>
        </div>
        
        <button type="submit" class="btn-primary">Crear Usuario</button>
    </form>
</div>

<!-- Tabla de usuarios -->
<div class="table-card">
    <h3>📋 Lista de Usuarios (<?= count($usuarios) ?>)</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td>
                            <?php if (!empty($u['foto_perfil'])): ?>
                                <img src="../datos/usuarios/<?= htmlspecialchars($u['foto_perfil']) ?>" class="mini-avatar">
                            <?php else: ?>
                                <div class="mini-avatar-placeholder"><?= strtoupper(substr($u['nombre'], 0, 1)) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['telefono']) ?></td>
                        <td>
                            <span class="badge badge-<?= $u['rol'] === 'administrador' ? 'admin' : 'user' ?>">
                                <?= ucfirst($u['rol']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($u['fecha_creacion'])) ?></td>
                        <td>
                            <?php if ($u['rol'] !== 'administrador'): ?>
                                <a href="?seccion=usuarios&eliminar=<?= $u['id'] ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('¿Eliminar este usuario?')">🗑️</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
