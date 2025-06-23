<?php
session_start(); 

include '../conexion.php'; // conexión a la base de datos

// Verifica que haya sesión activa
if (!isset($_SESSION['id'])) {
    echo "Error: Usuario no autenticado.";
    exit;
}

$usuario_id = $_SESSION['id'];
$tema = isset($_POST['tema']) ? $_POST['tema'] : 'Sin tema'; // ← ahora dinámico
$puntaje = isset($_POST['puntaje']) ? $_POST['puntaje'] : 0;

// Consulta para insertar resultado
$query = "INSERT INTO resultados (usuario_id, tema, puntaje, fecha) 
          VALUES ('$usuario_id', '$tema', '$puntaje', NOW())";

if ($conexion->query($query)) {
    echo "Puntaje guardado exitosamente.";
} else {
    echo "Error al guardar puntaje: " . $conexion->error;
}
?>
