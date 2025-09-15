<?php
session_start();
require_once '../modelo/modeloSesion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $login = new Login();

    if ($login->verificarUsuario($email, $password)) {
        $_SESSION['email'] = $email; // Guardar sesión
        header("Location: ../vista/app/inicio.php");
        exit();
    } else {
        header("Location: ../backend/rechazar.php");
        exit();
    }
} else {
    // si alguien entra directo sin POST
    header("Location: ../vista/sesion.php");
    exit();
}
