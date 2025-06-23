<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit;
}

$id_usuario = $_SESSION['id'];
$tema = 'Ecuaciones Lineales';

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
  <title>Examen - Ecuaciones Lineales</title>
  <link rel="stylesheet" href="../estilos/styles_examenes.css">
  <style>
    #modalResultado {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.6);
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
      top: 10px;
      right: 15px;
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
  <h1>Ecuaciones Lineales</h1>
  <form id="formActividades">
    <?php
      $opciones = [
        1 => ["2x + 3 = 11", ["x = 4", "x = 6", "x = 5"], "x = 4"],
        2 => ["5x - 10 = 0", ["x = 2", "x = -2", "x = 0"], "x = 2"],
        3 => ["3x + 2 = 11", ["x = 3", "x = 4", "x = 5"], "x = 3"],
        4 => ["4x - 8 = 0", ["x = 2", "x = -2", "x = 0"], "x = 2"]
      ];
      foreach ($opciones as $n => $opt) {
        echo "<div class=\"actividad\"><p><strong>{$n}.</strong> ¿Cuál es la solución de la ecuación: {$opt[0]}?</p><div class=\"respuesta\">";
        foreach ($opt[1] as $val) {
          echo "<label><input type=\"radio\" name=\"p$n\" value=\"$val\" required> $val</label>";
        }
        echo "</div></div>";
      }

      $rellenar = [
        5 => "Si 3x = 12, entonces x =",
        6 => "x/2 + 5 = 9. x =",
        7 => "-2x = 10. x =",
        8 => "x - 4 = -1. x ="
      ];
      foreach ($rellenar as $n => $txt) {
        echo "<div class=\"actividad\"><p><strong>{$n}.</strong> {$txt}</p><input type=\"text\" name=\"p$n\" required placeholder=\"Ingresa valor\"></div>";
      }
    ?>
    <div class="actividad"><p><strong>9.</strong> ¿Qué vale x en 6x + 3 = 15?</p><input type="number" name="p9" required></div>
    <div class="actividad"><p><strong>10.</strong> Si 7x - 7 = 0, entonces x =</p><input type="number" name="p10" required></div>

    <input type="hidden" name="tema" value="Ecuaciones Lineales">
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
    p1: "x = 4", p2: "x = 2", p3: "x = 3", p4: "x = 2",
    p5: "4", p6: "8", p7: "-5", p8: "3",
    p9: "2", p10: "1"
  };
  const explic = {
    p1: "2x+3=11 → x=4",
    p2: "5x−10=0 → x=2",
    p3: "3x+2=11 → x=3",
    p4: "4x−8=0 → x=2",
    p5: "3x=12 → x=4",
    p6: "x/2+5=9 → x=8",
    p7: "−2x=10 → x=−5",
    p8: "x−4=−1 → x=3",
    p9: "6x+3=15 → 6x=12 → x=2",
    p10: "7x−7=0 → x=1"
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
