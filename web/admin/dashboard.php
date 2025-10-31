<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../modelo/Admin.php';

$adminModel = new Admin();
$stats = $adminModel->obtenerEstadisticas();
$admin_nombre = $_SESSION['admin_nombre'];

$pagina = $_GET['seccion'] ?? 'inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../vista/assets/estilos/adminDashboard.css">
<title>Panel Admin - Chamba</title>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>Chamba Admin</h2>
        <p><?= htmlspecialchars($admin_nombre) ?></p>
    </div>
    
    <nav class="sidebar-nav">
        <a href="?seccion=inicio" class="<?= $pagina === 'inicio' ? 'active' : '' ?>">
            Chamba Estadisticas
        </a>
        <a href="?seccion=usuarios" class="<?= $pagina === 'usuarios' ? 'active' : '' ?>">
            Usuarios
        </a>
        <a href="?seccion=publicaciones" class="<?= $pagina === 'publicaciones' ? 'active' : '' ?>">
            Publicaciones
        </a>
        <a href="?seccion=mensajes" class="<?= $pagina === 'mensajes' ? 'active' : '' ?>">
            Mensajes
        </a>
        <a href="?seccion=resenas" class="<?= $pagina === 'resenas' ? 'active' : '' ?>">
            Reseñas
        </a>
        <a href="?seccion=categorias" class="<?= $pagina === 'categorias' ? 'active' : '' ?>">
            Categorías
        </a>
        <a href="logout.php" class="logout">
            Cerrar Sesión
        </a>
    </nav>
</aside>

<!-- Contenido Principal -->
<main class="main-content">
    <div class="top-bar">
        <h1>
            <?php
            $titulos = [
                'inicio' => 'Chamba Estadisticas',
                'usuarios' => 'Gestión de Usuarios',
                'publicaciones' => 'Gestión de Publicaciones',
                'mensajes' => 'Gestión de Mensajes',
                'resenas' => 'Gestión de Reseñas',
                'categorias' => 'Gestión de Categorías'
            ];
            echo $titulos[$pagina] ?? 'Panel de Administración';
            ?>
        </h1>
        <a href="../router.php?page=inicio" class="btn-ver-sitio" target="_blank">Ver Sitio</a>
    </div>

    <div class="content-area">
        <?php
        switch ($pagina) {
            case 'inicio':
                include __DIR__ . '/vista/dashboard_inicio.php';
                break;
            case 'usuarios':
                include __DIR__ . '/controlador/usuariosController.php';
                break;
            case 'publicaciones':
                include __DIR__ . '/controlador/publicacionesController.php';
                break;
            case 'mensajes':
                include __DIR__ . '/controlador/mensajesController.php';
                break;
            case 'resenas':
                include __DIR__ . '/controlador/resenasController.php';
                break;
            case 'categorias':
                include __DIR__ . '/controlador/categoriasController.php';
                break;
            default:
                echo '<p>Sección no encontrada</p>';
        }
        ?>
    </div>
</main>

</body>
</html>
