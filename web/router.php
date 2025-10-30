<?php
// web/router.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/modelo/config/conexion.php';

$page = $_GET['page'] ?? 'inicio';

switch ($page) {
    case 'inicio':
        require_once __DIR__ . '/controlador/inicioControler.php';
        break;

    case 'registro':
        // Si tienes controlador úsalo; si no, carga la vista directa
        require_once __DIR__ . '/vista/registro.php';
        break;

    case 'sesion':
        // Importante: NO pongas '/web/vista/...'; __DIR__ ya está en /web
        require_once __DIR__ . '/vista/sesion.php';
        break;

    case 'crear-publicacion':
    require_once __DIR__ . '/controlador/crearpubliControler.php';
        break;


    case 'perfil':
        require_once __DIR__ . '/controlador/perfilController.php';
        break;

    case 'editar-perfil':
        require_once __DIR__ . '/vista/app/usuario/editarPerfil.php';
        break;

    case 'mensajeria':
        require_once __DIR__ . '/controlador/mensajeriaControler.php';
        require_once __DIR__ . '/vista/mensajeria.php';
        break;

    default:
        http_response_code(404);
        echo "<h1>Error 404: Página no encontrada</h1>";
        break;
}
