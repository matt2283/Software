<?php
session_start();
include '../conexion.php'; // ajusta ruta si es necesario

// Consulta ranking
$query = "SELECT u.usuario, 
                 ROUND(AVG(r.puntaje), 2) AS promedio,
                 COUNT(r.id) AS examenes_realizados
          FROM resultados r
          JOIN usuarios u ON r.usuario_id = u.id
          GROUP BY u.id
          ORDER BY promedio DESC
          LIMIT 10";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Ranking de Mejores Promedios</title>
  <link rel="stylesheet" href="../estilos/styles_ranking.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

<div class="contenedor">
  <a href="../dashboard.php" class="btn-volver" aria-label="Volver a la página de actividades">
    <i class="fas fa-arrow-left"></i> Regresar
  </a>

  <h1>🏆 Ranking de Mejores Promedios</h1>

  <table aria-label="Tabla de ranking de usuarios con mejores promedios">
    <thead>
      <tr>
        <th>Posición</th>
        <th>Usuario</th>
        <th>Promedio</th>
        <th>Exámenes Presentados</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $posicion = 1;
      while ($fila = mysqli_fetch_assoc($resultado)) {
        $claseMedalla = '';
        if ($posicion == 1) $claseMedalla = '<i class="fas fa-medal oro medalla" title="1er lugar"></i>';
        elseif ($posicion == 2) $claseMedalla = '<i class="fas fa-medal plata medalla" title="2do lugar"></i>';
        elseif ($posicion == 3) $claseMedalla = '<i class="fas fa-medal bronce medalla" title="3er lugar"></i>';

        echo "<tr>
                <td>{$posicion} {$claseMedalla}</td>
                <td>" . htmlspecialchars($fila['usuario']) . "</td>
                <td>{$fila['promedio']}</td>
                <td>{$fila['examenes_realizados']}</td>
              </tr>";
        $posicion++;
      }

      if ($posicion == 1) {
        echo "<tr><td colspan='4' class='mensaje-vacio'>Aún no hay resultados para mostrar.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
