<?php
include '../conexion.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ERROR: ID no proporcionado'); window.location.href = '../admin_dashboard.php';</script>";
    exit();
}

$id = intval($_GET['id']); // Convertir a entero para evitar inyección básica

$sql = "DELETE FROM usuarios WHERE id = $id";

if ($conexion->query($sql) === TRUE) {
    echo "<script>alert('Usuario eliminado correctamente'); window.location.href = '../admin_dashboard.php';</script>";
} else {
    echo "<script>alert('Error al eliminar usuario: " . addslashes($conexion->error) . "'); window.location.href = '../admin_dashboard.php';</script>";
}

$conexion->close();
?>
