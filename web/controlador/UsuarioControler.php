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
            $nombre   = $_POST['nombre']   ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $edad     = $_POST['edad']     ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email    = $_POST['email']    ?? '';
            $password = $_POST['password'] ?? '';

            $resultado = $usuarioModel->registrarUsuario($nombre, $apellido, $edad, $telefono, $email, $password);

            if (!empty($resultado['success'])) {
                header('Location: /chamba/web/router.php?page=sesion&mensaje=' . urlencode('Usuario registrado, inicie sesión'));
                exit;
            } else {
                header('Location: /chamba/web/router.php?page=registro&error=' . urlencode($resultado['mensaje'] ?? 'Error de registro'));
                exit;
            }

        case 'login':
            $email    = $_POST['email']    ?? '';
            $password = $_POST['password'] ?? '';

            $usuario = $usuarioModel->iniciarSesion($email, $password);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre']     = $usuario['nombre'];
                $_SESSION['email']      = $usuario['email'];
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
