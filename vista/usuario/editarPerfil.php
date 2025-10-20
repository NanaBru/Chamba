<?php
require_once __DIR__ . "/../../controlador/editarPerfilControler.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../vista/estilos/registro.css">
<link rel="stylesheet" href="../../vista/estilos/styles.css">
<link rel="stylesheet" href="../../vista/estilos/stylesNav.css">
<link rel="stylesheet" href="../../vista/estilos/footer.css">
<link rel="shortcut icon" href="../../vista/img/logopng.png" type="image/x-icon">
<title>Editar Perfil</title>
<style>
    button { margin-top: 20px; padding: 10px 20px; background: #476a30; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; }
    a { text-decoration: none; margin-top: 20px; padding: 10px 20px; background: #476a30; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }

</style>
</head>
<body>

<section class="registro-seccion form-container">
    <h2>Editar Perfil</h2>

    <?php if(!empty($mensaje_error)): ?>
        <div class="mensaje error"><?php echo htmlspecialchars($mensaje_error); ?></div>
    <?php endif; ?>
    <?php if(!empty($mensaje_exito)): ?>
        <div class="mensaje exito"><?php echo htmlspecialchars($mensaje_exito); ?></div>
    <?php endif; ?>

    <form action="../../controlador/editarPerfilControler.php" method="post" id="formulario">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>

        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>

        <label for="edad">Edad</label>
        <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required min="18" max="99">

        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" maxlength="9" minlength="9" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

        <label for="password">Nueva Contraseña (opcional)</label>
        <input type="password" id="password" name="password" placeholder="Dejar en blanco si no desea cambiar">

        <button type="submit">Actualizar Perfil</button>
        <a href="perfil.php">Cancelar</a>
    </form>
</section>
 
<script src="../js/script.js"></script>
</body>
</html>
