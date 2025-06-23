<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
$nombreUsuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Alumno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="estilos/styles_dashboard.css">
</head>
<body>

    <div class="header">
        <img src="logo1.png" alt="Logo 1">
        <div class="usuario-sesion">Bienvenido, <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong></div>
        <img src="logo2.png" alt="Logo 2">
    </div>

    <div class="dashboard-container">
        <div class="dashboard">
            <div class="option" onclick="location.href='vistas/progreso.php'">
                <i class="fas fa-chart-line"></i>
                <span>Mi Progreso</span>
            </div>

            <div class="option" onclick="location.href='vistas/ranking.php'">
                <i class="fas fa-trophy"></i>
                <span>Ranking</span>
            </div>

            <div class="option" onclick="location.href='vistas/temas.php'">
                <i class="fas fa-book-open"></i>
                <span>Temas de Estudio</span>
            </div>

            <div class="option" onclick="location.href='vistas/actividades.php'">
                <i class="fas fa-tasks"></i>
                <span>Actividades</span>
            </div>
        </div>

        <button class="logout-button" onclick="location.href='controladores/cerrar_sesion.php'">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </button>
    </div>

    <footer>
        © Escuela 2025 - Todos los derechos reservados
    </footer>

</body>
</html>
