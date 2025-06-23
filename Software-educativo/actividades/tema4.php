<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit;
}

$id_usuario = $_SESSION['id'];
$tema = 'Despejes de x y y';

$query = "SELECT * FROM resultados WHERE usuario_id = '$id_usuario' AND tema = '$tema'";
$resultado = mysqli_query($conexion, $query);
if (mysqli_num_rows($resultado) > 0) {
  echo "<script>
          alert('Ya has contestado este examen.');
          window.location.href = '../vistas/actividades.php';
        </script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Examen - Despejes de x y y</title>
  <link rel="stylesheet" href="../estilos/styles_examenes.css" />
  <style>
    #modalResultado {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.6);
    }
    #modalResultado.mostrar {
      display: block;
    }
    #contenidoRetro {
      background: #fff;
      margin: 5% auto;
      padding: 20px;
      width: 90%;
      max-width: 600px;
      border-radius: 10px;
      position: relative;
      font-family: Arial, sans-serif;
    }
    #contenidoRetro p {
      margin-bottom: 10px;
    }
    #cerrarModalBtn {
      position: absolute;
      top: 10px; right: 15px;
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #555;
    }
    .correcto {
      background-color: #d4edda;
      color: #155724;
      padding: 8px;
      border-left: 5px solid #28a745;
      margin-bottom: 10px;
    }
    .incorrecto {
      background-color: #f8d7da;
      color: #721c24;
      padding: 8px;
      border-left: 5px solid #dc3545;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<div class="contenedor">
  <a href="../vistas/actividades.php" class="btn-volver">⬅ Volver a Temas</a>
  <h1>Despejes de x y y</h1>
  <form id="formActividades">
    <?php
      // Opciones múltiples P1 a P4
      $opciones = [
        1 => ["2x + 3y = 12, despeja x", ["x = (12 - 3y)/2", "x = (12 + 3y)/2", "x = (12 - y)/3"], "x = (12 - 3y)/2"],
        2 => ["4x - y = 7, despeja y", ["y = 4x - 7", "y = 7 - 4x", "y = 7 + 4x"], "y = 4x - 7"],
        3 => ["3x + 2y = 10, despeja y", ["y = (10 - 3x)/2", "y = (10 + 3x)/2", "y = (3x - 10)/2"], "y = (10 - 3x)/2"],
        4 => ["x - 5y = 15, despeja y", ["y = (x - 15)/5", "y = (15 - x)/5", "y = (15 + x)/5"], "y = (x - 15)/5"]
      ];
      foreach ($opciones as $n => $opt) {
        echo "<div class=\"actividad\"><p><strong>{$n}.</strong> {$opt[0]}</p><div class=\"respuesta\">";
        foreach ($opt[1] as $val) {
          echo "<label><input type=\"radio\" name=\"p$n\" value=\"$val\" required> $val</label>";
        }
        echo "</div></div>";
      }

      // Rellenar espacios P5 a P8
      $rellenar = [
        5 => "Despeja x: 5x + 4y = 20",
        6 => "Despeja y: 7x - 2y = 14",
        7 => "Despeja x: 3x - 6y = 9",
        8 => "Despeja y: 2x + 8y = 16"
      ];
      foreach ($rellenar as $n => $txt) {
        echo "<div class=\"actividad\"><p><strong>{$n}.</strong> {$txt}</p><input type=\"text\" name=\"p$n\" required placeholder=\"Ingresa expresión\"></div>";
      }
    ?>
    <!-- P9 y P10 respuestas numéricas -->
    <div class="actividad"><p><strong>9.</strong> Si despejas x en 2x + y = 5, ¿cuál es su valor cuando y=1?</p><input type="number" name="p9" required></div>
    <div class="actividad"><p><strong>10.</strong> En la ecuación x - 3y = 6, si y = 2, ¿cuánto vale x?</p><input type="number" name="p10" required></div>

    <input type="hidden" name="tema" value="Despejes de x y y">
    <button type="submit">Enviar respuestas</button>
  </form>
</div>

<div id="modalResultado">
  <div id="contenidoRetro">
    <button id="cerrarModalBtn">✖</button>
  </div>
</div>

<script>
  const respuestas = {
    p1: "x = (12 - 3y)/2", p2: "y = 4x - 7", p3: "y = (10 - 3x)/2", p4: "y = (x - 15)/5",
    p5: "x = (20 - 4y)/5", p6: "y = (7x - 14)/2", p7: "x = (9 + 6y)/3", p8: "y = (16 - 2x)/8",
    p9: "2", p10: "12"
  };

  const explic = {
    p1: "2x + 3y = 12 → x = (12 - 3y)/2",
    p2: "4x - y = 7 → y = 4x - 7",
    p3: "3x + 2y = 10 → y = (10 - 3x)/2",
    p4: "x - 5y = 15 → y = (x - 15)/5",
    p5: "5x + 4y = 20 → x = (20 - 4y)/5",
    p6: "7x - 2y = 14 → y = (7x - 14)/2",
    p7: "3x - 6y = 9 → x = (9 + 6y)/3",
    p8: "2x + 8y = 16 → y = (16 - 2x)/8",
    p9: "Despejando x en 2x + y = 5, con y=1 → 2x + 1=5 → x=2",
    p10: "x - 3y = 6, y=2 → x - 6=6 → x=12"
  };

  const modal = document.getElementById("modalResultado");
  const contenidoRetro = document.getElementById("contenidoRetro");
  const cerrarModalBtn = document.getElementById("cerrarModalBtn");
  const form = document.getElementById("formActividades");

  cerrarModalBtn.addEventListener("click", () => {
    modal.classList.remove('mostrar');
    contenidoRetro.querySelectorAll("p").forEach(el => el.remove());
    window.location.href = '../vistas/actividades.php';
  });

  function mostrarModal(html) {
    contenidoRetro.insertAdjacentHTML('beforeend', html);
    modal.classList.add('mostrar');
  }

  function evalua(num, val) {
    let ok = val.toString().trim() === respuestas[num];
    return `<p class="${ok ? 'correcto':'incorrecto'}"><strong>P${num}:</strong> ${ok ? 'Correcto ✅':'Incorrecto ❌'}<br>${explic[num]}</p>`;
  }

  form.addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(form);
    let total = 0, html = "";
    for (let i = 1; i <= 10; i++) {
      let key = 'p' + i, val = fd.get(key);
      html += evalua(key, val);
      if (val.toString().trim() === respuestas[key]) total++;
    }
    mostrarModal(html);
    fd.append("puntaje", total);
    fetch("../controladores/guardar_resultado.php", { method: "POST", body: fd });
  });
</script>
</body>
</html>
