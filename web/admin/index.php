<?php
session_start();

// Si ya está logueado como admin, redirigir al dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

require_once __DIR__ . '/../modelo/Usuario.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $userModel = new Usuario();
    $admin = $userModel->iniciarSesion($email, $password);
    
    if ($admin && $admin['rol'] === 'administrador') {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nombre'] = $admin['nombre'];
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciales inválidas o no tienes permisos de administrador";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../vista/assets/estilos/adminLogin.css">
<title>Admin - Chamba</title>
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h1>🔐 Panel de Administrador</h1>
        <p>Chamba - Sistema de Gestión</p>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Iniciar Sesión</button>
        </form>
        
        <a href="../router.php?page=inicio" class="volver">← Volver al sitio</a>
    </div>
</div>
</body>
</html>
