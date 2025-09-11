<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = new mysqli("localhost", "root", "", "bdChamba");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $email = $_POST["email"];
    $passwordIngresada = $_POST["password"];

    // Traer solo el hash de la contraseña desde la base
    $stmt = $conexion->prepare("SELECT password FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $passwordHash = $fila["password"];

        // Verificar la contraseña encriptada
        if (password_verify($passwordIngresada, $passwordHash)) {
            $_SESSION["email"] = $email;
          
            header("Location: ../backend/app/inicio.php");
            exit();
        } else {
            
            header("Location: http://yotube.com/dQw4w9WgXcQ");
            exit();
        }
    } else {
        
        header("Location: rechazar.php");
        exit();
    }

   
} else {
    echo "Acceso inválido, No esta activa la Base de Datos :(";
}
?>
