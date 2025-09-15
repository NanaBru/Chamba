<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['email'])) {
    header("Location: ../../frontend/iniciarsesion.html");
    exit;
}

require_once "../config/conexion.php";

$conn = Conexion::getConexion();

$email = $_SESSION['email'];

// Datos del usuario
$stmt = $conn->prepare("SELECT nombre, apellido FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resUser = $stmt->get_result();
$usuario = $resUser->fetch_assoc();

// Publicaciones
$sql = "SELECT p.titulo, p.descripcion, p.imagen, p.precio, p.fecha,
               u.nombre, u.apellido
        FROM publicaciones p
        JOIN usuario u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";

$result = $conn->query($sql);

// Convertimos a array para que la conexión pueda cerrarse
$publicaciones = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();

// ahora incluimos la vista, las variables $usuario y $publicaciones
// siguen en el mismo scope, así que inicio.php las puede usar.
?>
<?php include __DIR__ . '/../vista/app/inicio.php'; ?>