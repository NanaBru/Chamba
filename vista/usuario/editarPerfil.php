<?php
session_start();

// Redirigir al login si no hay sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../../frontend/iniciarsesion.html");
    exit;
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "chambaBD");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Obtener datos del usuario logueado
$stmt = $conn->prepare("SELECT nombre, apellido, edad, telefono, email FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../vista/estilos/registro.css">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../frontend/estilos/stylesNav.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            padding: 20px;
        }
        .form-container {
            width: 100%;
            max-width: 500px;
            height: auto;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2f3640;
        }
        .registro-seccion label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        .registro-seccion input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .registro-seccion button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #476a30;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        .registro-seccion button:hover {
            background:rgb(74, 92, 63);
            color:rgb(255, 255, 255);
        }
        .registro-seccion a {
            text-decoration: none;
            margin-top: 20px;
            padding: 10px 20px;
            background: #476a30;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <section class="registro-seccion">
        <form class="" action="../../controlador/actualizar_perfil.php" method="post" id="formulario">
            <h2>Editar Perfil</h2>

            <form action="../../controlador/actualizar_perfil.php" method="post">
                <div class="modal-nombre">
                    <label for="nombre">Nombre</label>
                   
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                </div>
            
            <div class="modal-nombre">
            <label for="edad">Edad</label>
            <input  type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required min="1" max="120">
            <label  for="telefono">Teléfono</label>

            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
            </div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

           

            <label for="password">Nueva Contraseña (opcional)</label>
            <input type="password" id="password" name="password" placeholder="Dejar en blanco si no desea cambiar">

            <button type="submit">Actualizar Perfil</button>
        </form>

        <a href="perfil.php">Cancelar</a>
    </section>

</body>
</html>
