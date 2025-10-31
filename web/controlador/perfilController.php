<?php
require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Publicaciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /chamba/web/router.php?page=sesion");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuarioModel = new Usuario();
$publicacionModel = new Publicaciones();

$usuario = $usuarioModel->obtenerDatosUsuario($usuario_id);

if (!$usuario) {
    echo "<p style='color:red;'>Error: No se encontraron los datos del usuario.</p>";
    exit;
}

$tieneFoto = false;
$fotoPath = '';
$inicial = strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1));

// Verificar foto existente
if (!empty($usuario['foto_perfil'])) {
    $fotoPath = '/chamba/web/datos/usuarios/' . $usuario['foto_perfil'];
    $rutaFisica = __DIR__ . '/../datos/usuarios/' . $usuario['foto_perfil'];
    $tieneFoto = file_exists($rutaFisica);
}

// Subida de nueva foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
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

        // Nombre único solo con nombre de archivo
        $nombreFoto = 'usuario_' . $usuario_id . '_' . uniqid() . '.' . $ext;
        $rutaDestino = $carpetaDestino . $nombreFoto;

        if (move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
            // Guardar SOLO el nombre en la base (sin rutas)
            $usuarioModel->actualizarFotoPerfilPorId($usuario_id, $nombreFoto);
            header("Location: /chamba/web/router.php?page=perfil");
            exit;
        }
    }
}

$misPublicaciones = $publicacionModel->getPublicacionesPorUsuario($usuario_id);

require_once __DIR__ . '/../vista/app/usuario/perfil.php';
