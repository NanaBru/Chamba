<?php
// web/controlador/crearpubliControler.php
// DEBUG TEMPORAL
if (empty($_SESSION['email'])) {
    echo "No hay email en sesión, redirigiendo...";
    exit;
}



require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../modelo/Publicaciones.php';

$userModel = new Usuario();
$pubModel  = new Publicaciones();

$mensaje = "";

$usuario = $userModel->getUserByEmail($_SESSION['email']);
if (!$usuario) {
    header("Location: /chamba/web/router.php?page=sesion&error=" . urlencode('Sesión inválida'));
    exit;
}
$usuarioId = (int)$usuario['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = $_POST['precio'] ?? 0;

    if ($titulo === '' || $descripcion === '' || !is_numeric($precio) || $precio < 0) {
        $mensaje = "Completa todos los campos válidos.";
    } else {
        $nombreImagen = null;
        
        if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $carpeta = __DIR__ . '/../datos/publicasiones/';
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg','jpeg','png','gif','webp'];
            
            if (!in_array($ext, $permitidas)) {
                $mensaje = "Formato de imagen no permitido.";
            } else {
                // Generar nombre único con extensión .webp
                $nombreImagen = uniqid('pub_') . '.webp';
                $destino = $carpeta . $nombreImagen;

                // Convertir imagen a WebP con calidad optimizada
                $tmpName = $_FILES['imagen']['tmp_name'];
                $converted = false;

                switch ($ext) {
                    case 'jpg':
                    case 'jpeg':
                        $img = imagecreatefromjpeg($tmpName);
                        if ($img) {
                            $converted = imagewebp($img, $destino, 85);
                            imagedestroy($img);
                        }
                        break;
                    case 'png':
                        $img = imagecreatefrompng($tmpName);
                        if ($img) {
                            imagepalettetotruecolor($img);
                            imagealphablending($img, true);
                            imagesavealpha($img, true);
                            $converted = imagewebp($img, $destino, 85);
                            imagedestroy($img);
                        }
                        break;
                    case 'gif':
                        $img = imagecreatefromgif($tmpName);
                        if ($img) {
                            $converted = imagewebp($img, $destino, 85);
                            imagedestroy($img);
                        }
                        break;
                    case 'webp':
                        $converted = move_uploaded_file($tmpName, $destino);
                        break;
                }

                if (!$converted) {
                    $mensaje = "No se pudo procesar la imagen.";
                    $nombreImagen = null;
                }
            }
        }

        if ($mensaje === "") {
            $ok = $pubModel->crearPublicacion($usuarioId, $titulo, $descripcion, $nombreImagen, (float)$precio);
            if ($ok) {
                header("Location: /chamba/web/router.php?page=inicio");
                exit;
            } else {
                $mensaje = "No se pudo crear la publicación.";
            }
        }
    }
}

require_once __DIR__ . '/../vista/app/crearpubli.php';
