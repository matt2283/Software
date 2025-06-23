<?php
session_start();
include 'conexion.php'; // Conexión externa con $conexion

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
$resultado = $conexion->query($query);

if ($resultado->num_rows > 0) {
    $usuario_data = $resultado->fetch_assoc();

    // Guardar información en sesión 
    $_SESSION['id'] = $usuario_data['id'];   
    $_SESSION['usuario'] = $usuario_data['usuario'];
    $_SESSION['es_admin'] = $usuario_data['es_admin'];

    if ($usuario_data['es_admin']) {
        echo "<script>window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "<script>window.location.href = 'dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href = 'login.php';</script>";
}

$conexion->close();
?>
