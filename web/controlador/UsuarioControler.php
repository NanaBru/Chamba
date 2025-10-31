<?php
// web/controlador/UsuarioControler.php
require_once __DIR__ . '/../modelo/Usuario.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioModel = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'registro':
            $nombre   = trim($_POST['nombre']   ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $edad     = (int)($_POST['edad']    ?? 0);
            $telefono = trim($_POST['telefono'] ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            $resultado = $usuarioModel->registrarUsuario($nombre, $apellido, $edad, $telefono, $email, $password);

            if (!empty($resultado['success'])) {
                header('Location: /chamba/web/router.php?page=sesion&mensaje=' . urlencode('Usuario registrado, inicie sesión'));
                exit;
            } else {
                header('Location: /chamba/web/router.php?page=registro&error=' . urlencode($resultado['mensaje'] ?? 'Error de registro'));
                exit;
            }

        case 'login':
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            $usuario = $usuarioModel->iniciarSesion($email, $password);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre']     = $usuario['nombre'];
                $_SESSION['email']      = $usuario['email'];
                // Si quieres guardar rol para panel admin, añade:
                $_SESSION['rol']        = $usuario['rol'] ?? 'usuario';
                header('Location: /chamba/web/router.php?page=inicio');
                exit;
            } else {
                header('Location: /chamba/web/router.php?page=sesion&error=' . urlencode('Credenciales incorrectas'));
                exit;
            }

        default:
            header('Location: /chamba/web/router.php?page=sesion&error=' . urlencode('Acción no válida'));
            exit; 
    }
}
