<?php
require_once '../modelo/modeloRegistro.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = new Usuario();

    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $edad     = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $email    = $_POST['email'];
    $password = $_POST['passwordA'];

    if ($usuario->registrar($nombre, $apellido, $edad, $telefono, $email, $password)) {
        session_start();
        $_SESSION['email'] = $email;
        header("Location: ../vista/usuario/perfil.php");
        exit();
    } else {
        header("Location: ../vista/registro.php?error=" . urlencode("Correo o teléfono ya están registrados"));
        exit();
    }
    
}
