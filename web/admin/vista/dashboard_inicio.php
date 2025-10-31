<!-- Estadísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <h3><?= $stats['total_usuarios'] ?></h3>
            <p>Usuarios Registrados</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-info">
            <h3><?= $stats['total_publicaciones'] ?></h3>
            <p>Publicaciones</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">💬</div>
        <div class="stat-info">
            <h3><?= $stats['total_mensajes'] ?></h3>
            <p>Mensajes</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-info">
            <h3><?= $stats['total_resenas'] ?></h3>
            <p>Reseñas</p>
        </div>
    </div>
</div>

<div class="quick-actions">
    <h2>Acciones Rápidas</h2>
    <div class="actions-grid">
        <a href="?seccion=usuarios" class="action-btn">
            <span>👥</span>
            Gestionar Usuarios
        </a>
        <a href="?seccion=publicaciones" class="action-btn">
            <span>📝</span>
            Ver Publicaciones
        </a>
        <a href="?seccion=mensajes" class="action-btn">
            <span>💬</span>
            Revisar Mensajes
        </a>
        <a href="?seccion=categorias" class="action-btn">
            <span>🏷️</span>
            Gestionar Categorías
        </a>
    </div>
</div>
