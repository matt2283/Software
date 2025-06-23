<?php
session_start();
include '../conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit;
}

$id_usuario = $_SESSION['id'];
$tema = 'Área y Perímetro'; 

// Consulta si ya contestó ese examen
$query = "SELECT * FROM resultados WHERE usuario_id = '$id_usuario' AND tema = '$tema'";
$resultado = mysqli_query($conexion, $query);

// Si ya lo contestó, redirige
if (mysqli_num_rows($resultado) > 0) {
  echo "<script>alert('Ya has contestado este examen.'); window.location.href = '../vistas/actividades.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Actividades - Área y Perímetro</title>
  <link rel="stylesheet" href="../estilos/styles_examenes.css" />
</head>
<body>

<div class="contenedor">
  <a href="../vistas/actividades.php" class="btn-volver" title="Volver a Temas">⬅ Volver a Temas</a>
  <h1>Área y Perímetro</h1>

  <form id="formActividades">

    <!-- (Preguntas igual que antes, pero agrego inputs ocultos para drag/drop y emparejamiento) -->

    <!-- 1. Selección múltiple -->
    <div class="actividad">
      <p><strong>1.</strong> ¿Cuál es el perímetro de un rectángulo que mide 7m de largo y 4m de ancho?</p>
      <div class="respuesta">
        <label><input type="radio" name="p1" value="22" required> 22 m</label>
        <label><input type="radio" name="p1" value="11"> 11 m</label>
        <label><input type="radio" name="p1" value="28"> 28 m</label>
      </div>
    </div>

    <!-- 2. Selección múltiple -->
    <div class="actividad">
      <p><strong>2.</strong> ¿Cuál es el área de un triángulo con base 6 cm y altura 5 cm?</p>
      <div class="respuesta">
        <label><input type="radio" name="p2" value="15" required> 15 cm²</label>
        <label><input type="radio" name="p2" value="30"> 30 cm²</label>
        <label><input type="radio" name="p2" value="11"> 11 cm²</label>
      </div>
    </div>

    <!-- 3. Drag and Drop -->
    <div class="actividad">
      <p><strong>3.</strong> Arrastra y suelta para ordenar las fórmulas del área correctamente:</p>
      <div class="drag-container">
        <div id="dragList" class="drag-list" aria-label="Elementos para arrastrar" tabindex="0">
          <div class="drag-item" draggable="true" id="item3a">A = b × h</div>
          <div class="drag-item" draggable="true" id="item3b">A = π × r²</div>
          <div class="drag-item" draggable="true" id="item3c">A = (B + b) / 2 × h</div>
        </div>
        <div id="dropList" class="drop-list" aria-label="Zona para soltar" tabindex="0" style="min-height: 50px; border: 1px solid #ccc; margin-top: 10px;"></div>
      </div>
      <small>Orden correcto: Rectángulo, Círculo, Trapecio</small>
    </div>
    <input type="hidden" name="p3_order" id="p3_order" />

    <!-- 4. Drag and Drop -->
    <div class="actividad">
      <p><strong>4.</strong> Arrastra las figuras geométricas a su perímetro correspondiente:</p>
      <div class="drag-container">
        <div id="dragList2" class="drag-list" aria-label="Figuras para arrastrar" tabindex="0">
          <div class="drag-item" draggable="true" id="item4a">Cuadrado</div>
          <div class="drag-item" draggable="true" id="item4b">Círculo</div>
          <div class="drag-item" draggable="true" id="item4c">Triángulo equilátero</div>
        </div>
        <div id="dropList2" class="drop-list" aria-label="Zona para soltar" tabindex="0" style="min-height: 50px; border: 1px solid #ccc; margin-top: 10px;"></div>
      </div>
      <small>Perímetros: 4 × lado, 2 × π × r, 3 × lado</small>
    </div>
    <input type="hidden" name="p4_order" id="p4_order" />

    <!-- 5. Rellenar espacios -->
    <div class="actividad">
      <p><strong>5.</strong> Completa: El perímetro de un círculo se calcula con la fórmula <em>P = ___ × r</em></p>
      <input type="text" id="p5" name="p5" required placeholder="Escribe el número π o 3.14" />
    </div>

    <!-- 6. Rellenar espacios -->
    <div class="actividad">
      <p><strong>6.</strong> Completa: El área de un cuadrado se calcula como lado al <em>___</em></p>
      <input type="text" id="p6" name="p6" required placeholder="Ejemplo: 2" />
    </div>

    <!-- 7. Emparejar columnas -->
    <div class="actividad">
      <p><strong>7.</strong> Empareja las figuras con su área correcta:</p>
      <div class="emparejar-grid" id="emparejarArea" style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
        <div class="emparejar-item" data-id="1" style="border:1px solid #ccc; padding:8px; cursor:pointer;">Cuadrado</div>
        <div class="emparejar-item" data-id="a" style="border:1px solid #ccc; padding:8px; cursor:pointer;">25 cm²</div>
        <div class="emparejar-item" data-id="2" style="border:1px solid #ccc; padding:8px; cursor:pointer;">Círculo (r=3)</div>
        <div class="emparejar-item" data-id="b" style="border:1px solid #ccc; padding:8px; cursor:pointer;">28.27 cm²</div>
        <div class="emparejar-item" data-id="3" style="border:1px solid #ccc; padding:8px; cursor:pointer;">Triángulo</div>
        <div class="emparejar-item" data-id="c" style="border:1px solid #ccc; padding:8px; cursor:pointer;">12 cm²</div>
      </div>
      <small>Selecciona pares de izquierda y derecha para emparejar</small>
    </div>
    <input type="hidden" name="p7_emparejados" id="p7_emparejados" />

    <!-- 8. Emparejar columnas -->
    <div class="actividad">
      <p><strong>8.</strong> Empareja los perímetros con sus fórmulas:</p>
      <div class="emparejar-grid" id="emparejarPerimetro" style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
        <div class="emparejar-item" data-id="1" style="border:1px solid #ccc; padding:8px; cursor:pointer;">Rectángulo</div>
        <div class="emparejar-item" data-id="a" style="border:1px solid #ccc; padding:8px; cursor:pointer;">2 × (largo + ancho)</div>
        <div class="emparejar-item" data-id="2" style="border:1px solid #ccc; padding:8px; cursor:pointer;">Cuadrado</div>
        <div class="emparejar-item" data-id="b" style="border:1px solid #ccc; padding:8px; cursor:pointer;">4 × lado</div>
        <div class="emparejar-item" data-id="3" style="border:1px solid #ccc; padding:8px; cursor:pointer;">Círculo</div>
        <div class="emparejar-item" data-id="c" style="border:1px solid #ccc; padding:8px; cursor:pointer;">2 × π × r</div>
      </div>
      <small>Selecciona pares para emparejar</small>
    </div>
    <input type="hidden" name="p8_emparejados" id="p8_emparejados" />

    <!-- 9. Respuesta numérica directa -->
    <div class="actividad">
      <p><strong>9.</strong> ¿Cuál es el perímetro de un cuadrado con lado 8 m?</p>
      <input type="number" id="p9" name="p9" required min="0" />
    </div>

    <!-- 10. Respuesta numérica directa -->
    <div class="actividad">
      <p><strong>10.</strong> ¿Cuál es el área de un triángulo con base 10 cm y altura 6 cm?</p>
      <input type="number" id="p10" name="p10" required min="0" />
    </div>
    
    <input type="hidden" name="tema" value="Área y Perímetro">

    <button type="submit">Enviar respuestas</button>
  </form>
</div>

<!-- Modal resultado -->
<div id="modalResultado" role="dialog" aria-modal="true" aria-labelledby="tituloModal" tabindex="-1">
  <div id="contenidoRetro" tabindex="0">
    <button id="cerrarModalBtn" aria-label="Cerrar retroalimentación">✖</button>
  </div>
</div>

<script>
  // Respuestas correctas
  const respuestasCorrectas = {
    p1: "22",
    p2: "15",
    p5: ["π", "3.14", "pi"], // aceptamos cualquiera
    p6: "2",
    p9: 32, // número
    p10: 30 // número
  };

  const explicaciones = {
    p1: "El perímetro es 2 × (largo + ancho) = 2 × (7 + 4) = 22 m.",
    p2: "El área del triángulo es (base × altura) / 2 = (6 × 5) / 2 = 15 cm².",
    p3: "El área del rectángulo es base × altura, del círculo es π × r², y del trapecio es ((B + b) / 2) × h.",
    p4: "El perímetro del cuadrado es 4 × lado, del círculo 2 × π × r, y del triángulo equilátero 3 × lado.",
    p5: "El perímetro del círculo es P = 2 × π × r, pero en esta fórmula solo se pide el factor π multiplicado por r.",
    p6: "El área del cuadrado se calcula como lado elevado al cuadrado (exponente 2).",
    p7: "Área del cuadrado: lado² = 25 cm², del círculo: π × r² ≈ 28.27 cm², del triángulo: (base × altura)/2 = 12 cm².",
    p8: "Perímetro rectángulo: 2 × (largo + ancho), cuadrado: 4 × lado, círculo: 2 × π × r.",
    p9: "Perímetro cuadrado = 4 × lado = 4 × 8 = 32 m.",
    p10: "Área triángulo = (base × altura)/2 = (10 × 6)/2 = 30 cm²."
  };

  // Drag and drop elementos
  const dragList = document.getElementById("dragList");
  const dropList = document.getElementById("dropList");

  const dragList2 = document.getElementById("dragList2");
  const dropList2 = document.getElementById("dropList2");

  // Emparejar columnas
  const emparejarArea = document.getElementById("emparejarArea");
  const emparejarPerimetro = document.getElementById("emparejarPerimetro");

  // Estado emparejados
  let emparejadosArea = {};
  let emparejadosPerimetro = {};

  // Drag & Drop básicos
  function allowDrop(ev) { ev.preventDefault(); }
  function drag(ev) { ev.dataTransfer.setData("text", ev.target.id); }

  function drop(ev) {
    ev.preventDefault();
    const data = ev.dataTransfer.getData("text");
    const dragged = document.getElementById(data);
    if (ev.target.id === "dropList" && !ev.target.contains(dragged)) {
      ev.target.appendChild(dragged);
    }
  }
  function drop2(ev) {
    ev.preventDefault();
    const data = ev.dataTransfer.getData("text");
    const dragged = document.getElementById(data);
    if (ev.target.id === "dropList2" && !ev.target.contains(dragged)) {
      ev.target.appendChild(dragged);
    }
  }

  // Inicializar drag & drop
  document.querySelectorAll(".drag-item").forEach(el => el.addEventListener("dragstart", drag));
  dropList.addEventListener("dragover", allowDrop);
  dropList.addEventListener("drop", drop);

  dropList2.addEventListener("dragover", allowDrop);
  dropList2.addEventListener("drop", drop2);

  // Emparejar con clic
  let selectedLeft = null;
  let selectedRight = null;
  function handleEmparejarClick(container, emparejados) {
    container.querySelectorAll('.emparejar-item').forEach(item => {
      item.onclick = () => {
        if (item.dataset.id.match(/^[1-3]$/)) { // lado izquierdo
          if (selectedLeft) selectedLeft.classList.remove('selected');
          selectedLeft = item;
          selectedLeft.classList.add('selected');
        } else { // lado derecho
          if (selectedRight) selectedRight.classList.remove('selected');
          selectedRight = item;
          selectedRight.classList.add('selected');
        }
        if (selectedLeft && selectedRight) {
          emparejados[selectedLeft.dataset.id] = selectedRight.dataset.id;
          selectedLeft.style.backgroundColor = '#2ecc71';
          selectedRight.style.backgroundColor = '#2ecc71';
          selectedLeft.classList.remove('selected');
          selectedRight.classList.remove('selected');
          selectedLeft = null;
          selectedRight = null;
        }
      };
    });
  }
  handleEmparejarClick(emparejarArea, emparejadosArea);
  handleEmparejarClick(emparejarPerimetro, emparejadosPerimetro);

  // Modal y elementos para feedback
  const modal = document.getElementById("modalResultado");
  const contenidoRetro = document.getElementById("contenidoRetro");
  const cerrarModalBtn = document.getElementById("cerrarModalBtn");
  const form = document.getElementById("formActividades");

  // Función para mostrar modal con feedback
  function mostrarModal(contenidoHTML) {
    contenidoRetro.insertAdjacentHTML('beforeend', contenidoHTML);
    modal.classList.add('mostrar');
    modal.focus();
  }

  // Cerrar modal
    cerrarModalBtn.addEventListener("click", () => {
      modal.classList.remove('mostrar');
      contenidoRetro.querySelectorAll("ol, p, li").forEach(el => el.remove());
      window.location.href = '../vistas/actividades.php';
    });

    window.addEventListener("click", (event) => {
      if (event.target === modal) {
        modal.classList.remove('mostrar');
        contenidoRetro.querySelectorAll("ol, p, li").forEach(el => el.remove());
        window.location.href = '../vistas/actividades.php';
      }
    });

  // Antes de enviar el formulario: llenar inputs ocultos con estado actual de drag & drop y emparejados
  function actualizarInputsOcultos() {
    // Pregunta 3 orden drag & drop
    const ordenP3 = Array.from(dropList.children).map(e => e.id);
    document.getElementById('p3_order').value = ordenP3.join(',');

    // Pregunta 4 orden drag & drop
    const ordenP4 = Array.from(dropList2.children).map(e => e.id);
    document.getElementById('p4_order').value = ordenP4.join(',');

    // Pregunta 7 emparejados (objeto) convertido a string JSON
    document.getElementById('p7_emparejados').value = JSON.stringify(emparejadosArea);

    // Pregunta 8 emparejados
    document.getElementById('p8_emparejados').value = JSON.stringify(emparejadosPerimetro);
  }

  // Función para evaluar pregunta simple y generar feedback HTML
  function evaluarPregunta(num, respUsuario, respCorrecta, explicacion) {
    let correcto = false;
    if (Array.isArray(respCorrecta)) {
      // Si la respuesta correcta es arreglo, comparar ignorando mayúsculas y espacios
      correcto = respCorrecta.some(c => c.toLowerCase() === respUsuario.toLowerCase().trim());
    } else if (typeof respCorrecta === 'number') {
      correcto = Number(respUsuario) === respCorrecta;
    } else {
      correcto = respUsuario === respCorrecta;
    }
    let clase = correcto ? 'correcto' : 'incorrecto';
    return `<p class="${clase}"><strong>Pregunta ${num}:</strong> ${correcto ? 'Correcto ✅' : 'Incorrecto ❌'}<br>${explicacion}</p>`;
  }

  // Evaluar orden de arrastrar y soltar p3
  function evaluarOrdenP3(orden) {
    const correcto = orden.join(',') === "item3a,item3b,item3c";
    return correcto;
  }

  // Evaluar orden p4 (drag and drop perímetros)
  function evaluarOrdenP4(orden) {
    const correcto = orden.join(',') === "item4a,item4b,item4c";
    return correcto;
  }

  // Evaluar emparejados para p7 y p8
  function evaluarEmparejados(pregunta, emparejados) {
    // Correctos para p7
    const correctosP7 = { "1": "a", "2": "b", "3": "c" };
    const correctosP8 = { "1": "a", "2": "b", "3": "c" };
    let correctos = pregunta === 7 ? correctosP7 : correctosP8;
    let total = Object.keys(correctos).length;
    let acertadas = 0;
    for (const key in correctos) {
      if (emparejados[key] === correctos[key]) acertadas++;
    }
    return acertadas === total;
  }




    form.addEventListener("submit", function(event) {
    event.preventDefault();
    actualizarInputsOcultos();

    const formData = new FormData(form);
    let puntaje = 0;
    let resultadosHTML = "";

    // Pregunta 1
    const p1HTML = evaluarPregunta(1, formData.get("p1"), respuestasCorrectas.p1, explicaciones.p1);
    resultadosHTML += p1HTML;
    if (p1HTML.includes("✅")) puntaje++;

    // Pregunta 2
    const p2HTML = evaluarPregunta(2, formData.get("p2"), respuestasCorrectas.p2, explicaciones.p2);
    resultadosHTML += p2HTML;
    if (p2HTML.includes("✅")) puntaje++;

    // Pregunta 3
    const ordenP3 = formData.get("p3_order").split(",");
    const p3Ok = evaluarOrdenP3(ordenP3);
    if (p3Ok) puntaje++;
    resultadosHTML += `<p class="${p3Ok ? 'correcto' : 'incorrecto'}"><strong>Pregunta 3:</strong> ${p3Ok ? 'Correcto ✅' : 'Incorrecto ❌'}<br>${explicaciones.p3}</p>`;

    // Pregunta 4
    const ordenP4 = formData.get("p4_order").split(",");
    const p4Ok = evaluarOrdenP4(ordenP4);
    if (p4Ok) puntaje++;
    resultadosHTML += `<p class="${p4Ok ? 'correcto' : 'incorrecto'}"><strong>Pregunta 4:</strong> ${p4Ok ? 'Correcto ✅' : 'Incorrecto ❌'}<br>${explicaciones.p4}</p>`;

    // Pregunta 5
    const p5HTML = evaluarPregunta(5, formData.get("p5"), respuestasCorrectas.p5, explicaciones.p5);
    resultadosHTML += p5HTML;
    if (p5HTML.includes("✅")) puntaje++;

    // Pregunta 6
    const p6HTML = evaluarPregunta(6, formData.get("p6"), respuestasCorrectas.p6, explicaciones.p6);
    resultadosHTML += p6HTML;
    if (p6HTML.includes("✅")) puntaje++;

    // Pregunta 7
    const p7Emp = JSON.parse(formData.get("p7_emparejados") || "{}");
    const p7Ok = evaluarEmparejados(7, p7Emp);
    if (p7Ok) puntaje++;
    resultadosHTML += `<p class="${p7Ok ? 'correcto' : 'incorrecto'}"><strong>Pregunta 7:</strong> ${p7Ok ? 'Correcto ✅' : 'Incorrecto ❌'}<br>${explicaciones.p7}</p>`;

    // Pregunta 8
    const p8Emp = JSON.parse(formData.get("p8_emparejados") || "{}");
    const p8Ok = evaluarEmparejados(8, p8Emp);
    if (p8Ok) puntaje++;
    resultadosHTML += `<p class="${p8Ok ? 'correcto' : 'incorrecto'}"><strong>Pregunta 8:</strong> ${p8Ok ? 'Correcto ✅' : 'Incorrecto ❌'}<br>${explicaciones.p8}</p>`;

    // Pregunta 9
    const p9HTML = evaluarPregunta(9, formData.get("p9"), respuestasCorrectas.p9, explicaciones.p9);
    resultadosHTML += p9HTML;
    if (p9HTML.includes("✅")) puntaje++;

    // Pregunta 10
    const p10HTML = evaluarPregunta(10, formData.get("p10"), respuestasCorrectas.p10, explicaciones.p10);
    resultadosHTML += p10HTML;
    if (p10HTML.includes("✅")) puntaje++;

    // Mostrar retroalimentación en modal
    mostrarModal(resultadosHTML);

    // Enviar resultado al servidor con puntaje
    formData.append("puntaje", puntaje);

    fetch('../controladores/guardar_resultado.php', {
      method: 'POST',
      body: formData
    })
  });
</script>

</body>
</html>
