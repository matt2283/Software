<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Resultados del usuario
$query = "SELECT tema, puntaje, fecha FROM resultados WHERE usuario_id = '$id_usuario' ORDER BY fecha DESC";
$resultado = mysqli_query($conexion, $query);

// Calcular avance
$total_temas = 5;
$query_temas = "SELECT COUNT(DISTINCT tema) AS temas_completados FROM resultados WHERE usuario_id = '$id_usuario'";
$resultado_temas = mysqli_query($conexion, $query_temas);
$fila_temas = mysqli_fetch_assoc($resultado_temas);
$temas_completados = $fila_temas['temas_completados'];
$porcentaje_avance = round(($temas_completados / $total_temas) * 100);

// Determinar medalla
if ($porcentaje_avance === 100) {
    $medalla = "🥇 Maestro del Saber";
} elseif ($porcentaje_avance >= 61) {
    $medalla = "🟢 Avanzado";
} elseif ($porcentaje_avance >= 21) {
    $medalla = "🔵 Aprendiz";
} else {
    $medalla = "🟤 Explorador";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Mi Progreso</title>
  <link rel="stylesheet" href="../estilos/styles_progreso.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="contenedor">
    <a href="../dashboard.php" class="btn-volver">
      <i class="fas fa-arrow-left"></i> Regresar
    </a>
    <h1><i class="fas fa-chart-line"></i> Mi Progreso</h1>

    <!-- Barra de progreso -->
    <div class="progreso-box">
      <label for="barra-progreso">Avance general: <?php echo $porcentaje_avance; ?>%</label>
      <div class="barra-progreso">
        <div class="progreso" style="width: <?php echo $porcentaje_avance; ?>%"></div>
      </div>
    </div>

    <!-- Medalla -->
    <div class="medalla-box">
      <p>🏅 Has obtenido la medalla: <strong><?php echo $medalla; ?></strong></p>
    </div>

    <!-- Tabla de resultados -->
    <?php if (mysqli_num_rows($resultado) > 0): ?>
      <div class="tabla-container">
        <table>
          <thead>
            <tr>
              <th><i class="fas fa-book"></i> Tema</th>
              <th><i class="fas fa-star"></i> Puntaje</th>
              <th><i class="fas fa-clock"></i> Fecha y Hora</th>
            </tr>
          </thead>
          <tbody>
            <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
            <tr>
              <td><?php echo htmlspecialchars($fila['tema']); ?></td>
              <td><?php echo intval($fila['puntaje']); ?> / 10</td>
              <td><?php echo date('d/m/Y H:i', strtotime($fila['fecha'])); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="mensaje-vacio"><i class="fas fa-info-circle"></i> Aún no has registrado ningún progreso.</p>
    <?php endif; ?>
  </div>
</body>
</html>
