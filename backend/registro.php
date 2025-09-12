<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "bdChamba");

    //mysqli (MySQL Mejorado o Improved) es una extensión de PHP que permite interactuar con bases de datos MySQL.

    // Obtener datos del formulario
    $nombre = $conexion->real_escape_string($_POST["nombre"]);
    $apellido = $conexion->real_escape_string($_POST["apellido"]);
    $cedula = $conexion->real_escape_string($_POST["cedula"]);
    $edad = $conexion->real_escape_string($_POST["edad"]);
    $telefono = $conexion->real_escape_string($_POST["telefono"]);
    $rol = $conexion->real_escape_string($_POST["rol"]);
    $email = $conexion->real_escape_string($_POST["email"]);
    $password = $conexion->real_escape_string($_POST["passwordA"]);
    $password2 = $conexion->real_escape_string($_POST["passwordB"]);

    /*
    El método real_escape_string es importante cuando se trabaja con datos proporcionados
    por los usuarios, garantizando que los datos no comprometan la seguridad de tu base de datos.
    */

    // Verificar si el usuario o el email ya existen en la base de datos
    $sql_check = "SELECT 1 FROM usuario WHERE cedula = '$cedula' OR email = '$email'";
    $resultado = $conexion->query($sql_check);


    if ($resultado->num_rows > 0) {
        // Redirigir si existe un usuario con el mismo nombre o correo
        header("Location: recahezar.php");
        exit();
    }


    //Encriptar la contraseña e insertar el usuario en la base de datos
    $password_encriptada = password_hash($password, PASSWORD_BCRYPT);
    $sql_insert = "INSERT INTO usuario (nombre, apellido, cedula, edad, telefono, rol, email, password) VALUES ('$nombre', '$apellido', '$cedula', '$edad', '$telefono', '$rol', '$email', '$password_encriptada')";


    if ($conexion->query($sql_insert) === TRUE) {
    // Guardar sesión del usuario
    $_SESSION['email'] = $email;

    // Redirigir al perfil
    header("Location: ../backend/usuario/perfil.php");
    exit();
    
}



}

