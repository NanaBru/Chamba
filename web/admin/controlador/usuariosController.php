<?php
$adminModel = new Admin();

// Crear usuario
if (isset($_POST['crear_usuario'])) {
    $datos = [
        'nombre' => trim($_POST['nombre']),
        'apellido' => trim($_POST['apellido']),
        'edad' => (int)$_POST['edad'],
        'telefono' => trim($_POST['telefono']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'rol' => $_POST['rol']
    ];
    
    if ($adminModel->crearUsuario($datos)) {
        $mensaje_exito = "Usuario creado correctamente";
    } else {
        $mensaje_error = "Error al crear usuario";
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    if ($adminModel->eliminarUsuario($id)) {
        header("Location: ?seccion=usuarios&eliminado=1");
        exit;
    }
}

// Obtener todos los usuarios
$usuarios = $adminModel->obtenerTodosUsuarios();

include __DIR__ . '/../vista/usuarios.php';
