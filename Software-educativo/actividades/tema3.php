<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit;
}

$id_usuario = $_SESSION['id'];
$tema = 'Binomios y Trinomios';

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
  <meta charset="UTF-8">
  <title>Examen - Binomios y Trinomios</title>
  <link rel="stylesheet" href="../estilos/styles_examenes.css">
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
  <h1>Binomios y Trinomios</h1>
  <form id="formActividades">
    <?php
      // Preguntas opción múltiple P1 a P4
      $opciones = [
        1 => ["(x + 3)(x + 2)", ["x² + 5x + 6", "x² + 6x + 5", "x² + 3x + 2"], "x² + 5x + 6"],
        2 => ["(x - 1)(x + 4)", ["x² + 3x - 4", "x² + 4x - 1", "x² + 5x - 4"], "x² + 3x - 4"],
        3 => ["(2x + 3)(x + 1)", ["2x² + 5x + 3", "2x² + 3x + 1", "2x² + 4x + 3"], "2x² + 5x + 3"],
        4 => ["(x + 5)²", ["x² + 10x + 25", "x² + 5x + 25", "x² + 25"], "x² + 10x + 25"]
      ];
      foreach ($opciones as $n => $opt) {
        echo "<div class=\"actividad\"><p><strong>{$n}.</strong> ¿Cuál es la expansión de {$opt[0]}?</p><div class=\"respuesta\">";
        foreach ($opt[1] as $val) {
          echo "<label><input type=\"radio\" name=\"p$n\" value=\"$val\" required> $val</label>";
        }
        echo "</div></div>";
      }

      // Preguntas rellenar espacios P5 a P8
      $rellenar = [
        5 => "Expande el binomio: (x - 3)(x + 3) =",
        6 => "Factoriza el trinomio: x² + 7x + 12 =",
        7 => "Expresa el trinomio: (x + 2)(x + 5) =",
        8 => "Factoriza: x² - 9 ="
      ];
      foreach ($rellenar as $n => $txt) {
        echo "<div class=\"actividad\"><p><strong>{$n}.</strong> {$txt}</p><input type=\"text\" name=\"p$n\" required placeholder=\"Ingresa expresión o respuesta\"></div>";
      }
    ?>
    <!-- P9 y P10 respuestas numéricas -->
    <div class="actividad"><p><strong>9.</strong> ¿Cuál es el coeficiente de x en la expansión de (x + 4)(x - 2)?</p><input type="number" name="p9" required></div>
    <div class="actividad"><p><strong>10.</strong> ¿Cuál es el término independiente en la expansión de (2x + 1)(x - 3)?</p><input type="number" name="p10" required></div>

    <input type="hidden" name="tema" value="Binomios y Trinomios">
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
    p1: "x² + 5x + 6", p2: "x² + 3x - 4", p3: "2x² + 5x + 3", p4: "x² + 10x + 25",
    p5: "x² - 9", p6: "(x + 3)(x + 4)", p7: "x² + 7x + 10", p8: "(x - 3)(x + 3)",
    p9: "2", p10: "-6"
  };

  const explic = {
    p1: "(x+3)(x+2) = x² + 5x + 6",
    p2: "(x-1)(x+4) = x² + 3x - 4",
    p3: "(2x+3)(x+1) = 2x² + 5x + 3",
    p4: "(x+5)² = x² + 10x + 25",
    p5: "(x - 3)(x + 3) = x² - 9",
    p6: "x² + 7x + 12 = (x + 3)(x + 4)",
    p7: "(x + 2)(x + 5) = x² + 7x + 10",
    p8: "x² - 9 = (x - 3)(x + 3)",
    p9: "Coeficiente de x en (x + 4)(x - 2) es 2",
    p10: "Término independiente en (2x + 1)(x - 3) es -6"
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
