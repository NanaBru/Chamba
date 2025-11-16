<?php
require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Publicaciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioModel = new Usuario();
$publicacionModel = new Publicaciones();

// Detectar si es perfil propio o ajeno
$usuario_id_perfil = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['usuario_id'] ?? 0);

// Si no hay ID en sesión ni en GET, redirigir a login
if ($usuario_id_perfil <= 0) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

// Obtener datos del usuario a mostrar
$usuario = $usuarioModel->obtenerDatosUsuario($usuario_id_perfil);

if (!$usuario) {
    header("Location: /chamba/web/router.php?page=inicio");
    exit;
}

// Determinar si es el propio perfil
$esMiPerfil = isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $usuario_id_perfil;

// Subida de foto (solo si es mi perfil)
if ($esMiPerfil && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $foto = $_FILES['foto_perfil'];

    if ($foto['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = __DIR__ . '/../datos/usuarios/';
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png','gif','webp'];
        
        if (!in_array($ext, $permitidas)) {
            header("Location: /chamba/web/router.php?page=perfil&error=" . urlencode('Formato no permitido'));
            exit;
        }

        $nombreFoto = 'usuario_' . $usuario_id_perfil . '_' . uniqid() . '.' . $ext;
        $rutaDestino = $carpetaDestino . $nombreFoto;

        if (move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
            $usuarioModel->actualizarFotoPerfilPorId($usuario_id_perfil, $nombreFoto);
            header("Location: /chamba/web/router.php?page=perfil");
            exit;
        }
    }
}

// Preparar datos de foto
$tieneFoto = false;
$fotoPath = '';
$inicial = strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1));

if (!empty($usuario['foto_perfil'])) {
    $fotoPath = '/chamba/web/datos/usuarios/' . $usuario['foto_perfil'];
    $rutaFisica = __DIR__ . '/../datos/usuarios/' . $usuario['foto_perfil'];
    $tieneFoto = file_exists($rutaFisica);
}

// Obtener publicaciones
$misPublicaciones = $publicacionModel->getPublicacionesPorUsuario($usuario_id_perfil);

require_once __DIR__ . '/../vista/app/usuario/perfil.php';
