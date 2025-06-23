<?php
$host = "localhost";
$usuario = "root";       // Cambia esto si tu usuario es diferente
$contrasena = "";        // Cambia esto si tienes contraseña en tu servidor MySQL
$base_de_datos = "software_educativo"; 

$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
