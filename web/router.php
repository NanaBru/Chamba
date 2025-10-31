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
    require_once __DIR__ . '/controlador/registroController.php';
    break;

    case 'sesion':
    require_once __DIR__ . '/controlador/sesionController.php';
    break;


    case 'crear-publicacion':
    require_once __DIR__ . '/controlador/crearpubliControler.php';
    break;

    case 'publicacion':
    require_once __DIR__ . '/controlador/publicacionController.php';
    break;

    case 'perfil':
        require_once __DIR__ . '/controlador/perfilController.php';
        break;

    case 'editar-perfil':
    require_once __DIR__ . '/controlador/editarPerfilControler.php';
    break;

    case 'chat':
    require_once __DIR__ . '/controlador/chatController.php';
    break;



    case 'mensajeria':
        require_once __DIR__ . '/controlador/mensajeriaControler.php';
        require_once __DIR__ . '/vista/mensajeria.php';
        break;
    
    case 'mis-solicitudes':
    require_once __DIR__ . '/controlador/misSolicitudesController.php';
    break;


    default:
        http_response_code(404);
        echo "<h1>Error 404: Página no encontrada</h1>";
        break;
    
        
}
