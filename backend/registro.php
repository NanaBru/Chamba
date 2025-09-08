<?php
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
    $email = $conexion->real_escape_string($_POST["email"]);
    $password = $conexion->real_escape_string($_POST["passwordA"]);
    $password2 = $conexion->real_escape_string($_POST["passwordB"]);

    /*
    El método real_escape_string es importante cuando se trabaja con datos proporcionados
    por los usuarios, garantizando que los datos no comprometan la seguridad de tu base de datos.
    */

    // Verificar si el usuario o el email ya existen en la base de datos
    $sql_check = "SELECT 1 FROM usuario WHERE nombre = '$nombre' OR email = '$email'";
    $resultado = $conexion->query($sql_check);

    
    /* 
    SELECT 1 ese comando se usa para seleccionar datos de una base de datos.
    Esta consulta selecciona el valor 1 para cada fila que coincide con la condición WHERE.
    Se utiliza solo para verificar la existencia de registros.

    FROM especifica la tabla desde la cual se deben recuperar los datos y
    usuarios es el nombre de la tabla en la base de datos donde se buscarán los registros.
    */

    if ($resultado->num_rows > 0) {
        // Redirigir si existe un usuario con el mismo nombre o correo
        header("Location: rechazar.php");
        exit();
    }

    //En PHP, num_rows > 0 se utiliza para verificar si una consulta SQL que devuelve resultados
    //tiene al menos una fila.

    //Encriptar la contraseña e insertar el usuario en la base de datos
    $password_encriptada = password_hash($password, PASSWORD_BCRYPT);
    $sql_insert = "INSERT INTO usuario (nombre, apellido, cedula, edad, telefono, email, password) VALUES ('$nombre', '$apellido', '$cedula', '$edad', '$telefono', '$email', '$password_encriptada')";

    //Verificar si el usuario se registró correctamente
    if ($conexion->query($sql_insert) === TRUE) {
        //$sql_insert contiene la consulta SQL que se desea ejecutar.
        header("Location: confirmar.php");
        exit();
    }

    // Cerrar la conexión
    $conexion->close();
}

