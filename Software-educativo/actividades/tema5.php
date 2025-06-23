<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit;
}

$id_usuario = $_SESSION['id'];
$tema = 'Teorema de Pitágoras';

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
  <title>Examen - Teorema de Pitágoras</title>
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
  <h1>Teorema de Pitágoras</h1>
  <form id="formActividades">
    <?php
      // Opciones múltiples P1 a P4
      $opciones = [
        1 => ["Un triángulo rectángulo tiene catetos de 3 y 4. ¿Cuál es la hipotenusa?", ["5", "6", "7"], "5"],
        2 => ["Catetos 5 y 12, ¿cuánto mide la hipotenusa?", ["12", "13", "14"], "13"],
        3 => ["Catetos 8 y 15, hipotenusa es:", ["17", "16", "18"], "17"],
        4 => ["Hipotenusa 10 y cateto 6, ¿cuánto mide el otro cateto?", ["7", "8", "9"], "8"]
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
        5 => "Si un cateto mide 9 y la hipotenusa 15, el otro cateto mide:",
        6 => "Hipotenusa 13 y cateto 5, el otro cateto mide:",
        7 => "Hipotenusa 25 y un cateto 7, otro cateto mide:",
        8 => "Catetos 7 y 24, ¿cuánto mide la hipotenusa?"
      ];
      foreach ($rellenar as $n => $txt) {
        echo "<div class=\"actividad\"><p><strong>{$n}.</strong> {$txt}</p><input type=\"text\" name=\"p$n\" required placeholder=\"Ingresa valor\"></div>";
      }
    ?>
    <!-- P9 y P10 respuestas numéricas -->
    <div class="actividad"><p><strong>9.</strong> Calcula la hipotenusa de un triángulo con catetos 12 y 16.</p><input type="number" name="p9" required></div>
    <div class="actividad"><p><strong>10.</strong> Un triángulo tiene hipotenusa 20 y un cateto 16, ¿cuánto mide el otro cateto?</p><input type="number" name="p10" required></div>

    <input type="hidden" name="tema" value="Teorema de Pitágoras">
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
    p1: "5", p2: "13", p3: "17", p4: "8",
    p5: "12", p6: "12", p7: "24", p8: "25",
    p9: "20", p10: "12"
  };

  const explic = {
    p1: "3^2 + 4^2 = 9 + 16 = 25 → √25 = 5",
    p2: "5^2 + 12^2 = 25 + 144 = 169 → √169 = 13",
    p3: "8^2 + 15^2 = 64 + 225 = 289 → √289 = 17",
    p4: "10^2 - 6^2 = 100 - 36 = 64 → √64 = 8",
    p5: "15^2 - 9^2 = 225 - 81 = 144 → √144 = 12",
    p6: "13^2 - 5^2 = 169 - 25 = 144 → √144 = 12",
    p7: "25^2 - 7^2 = 625 - 49 = 576 → √576 = 24",
    p8: "7^2 + 24^2 = 49 + 576 = 625 → √625 = 25",
    p9: "12^2 + 16^2 = 144 + 256 = 400 → √400 = 20",
    p10: "20^2 - 16^2 = 400 - 256 = 144 → √144 = 12"
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
