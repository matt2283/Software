<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Temas de Estudio</title>
    <link rel="stylesheet" href="../estilos/styles_temas.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="acciones-superiores" role="navigation" aria-label="Enlaces superiores">
        <a href="../dashboard.php" title="Volver al Dashboard"><i class="fas fa-home" aria-hidden="true"></i> Inicio</a>
        <a href="../controladores/cerrar_sesion.php" title="Cerrar sesión"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Cerrar sesión</a>
    </div>

    <div class="container" role="main">
        <h1>Temas de Estudio</h1>

        <div class="progress-container" aria-label="Barra de progreso de subtemas vistos">
            <div class="progress-bar">
                <div class="progress-fill" id="barraProgreso"></div>
            </div>
        </div>

        <div class="tema" role="region" aria-labelledby="tema1-titulo">
            <h2 id="tema1-titulo" tabindex="0" onclick="toggleSubtemas(this)" onkeypress="if(event.key==='Enter'){toggleSubtemas(this);}">Tema 1: Introducción a HTML</h2>
            <div class="subtemas" style="display:none;">
                <div class="subtema" data-id="contenido1" tabindex="0" role="button" onclick="abrirSubtema(0)" onkeypress="if(event.key==='Enter'){abrirSubtema(0);}"><span>Estructura básica de un documento HTML</span></div>
                <div class="subtema" data-id="contenido2" tabindex="0" role="button" onclick="abrirSubtema(1)" onkeypress="if(event.key==='Enter'){abrirSubtema(1);}"><span>Etiquetas principales (head, body, title)</span></div>
            </div>
        </div>

        <div class="tema" role="region" aria-labelledby="tema2-titulo">
            <h2 id="tema2-titulo" tabindex="0" onclick="toggleSubtemas(this)" onkeypress="if(event.key==='Enter'){toggleSubtemas(this);}">Tema 2: Estilos con CSS</h2>
            <div class="subtemas" style="display:none;">
                <div class="subtema" data-id="contenido3" tabindex="0" role="button" onclick="abrirSubtema(2)" onkeypress="if(event.key==='Enter'){abrirSubtema(2);}"><span>Introducción a CSS</span></div>
                <div class="subtema" data-id="contenido4" tabindex="0" role="button" onclick="abrirSubtema(3)" onkeypress="if(event.key==='Enter'){abrirSubtema(3);}"><span>Colores, fuentes y márgenes</span></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="modalContenido" role="dialog" aria-modal="true" aria-labelledby="tituloModal" tabindex="-1">
        <div class="modal-content">
            <div class="modal-header">
                <div class="titulo" id="tituloModal">Tema - Subtema</div>
                <button class="btn-close" onclick="cerrarModal()" aria-label="Cerrar">&times;</button>
            </div>
            <div class="modal-body" id="contenidoModal" tabindex="0">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button class="btn" id="btnPrev" onclick="cambiarSubtema(-1)">← Anterior</button>
                <button class="btn" id="btnNext" onclick="cambiarSubtema(1)">Siguiente →</button>
            </div>
        </div>
    </div>

    <script>
        const temas = [
            {
                nombre: "Tema 1: Introducción a HTML",
                subtemas: [
                    { id: "contenido1", titulo: "Estructura básica de un documento HTML" },
                    { id: "contenido2", titulo: "Etiquetas principales (head, body, title)" }
                ]
            },
            {
                nombre: "Tema 2: Estilos con CSS",
                subtemas: [
                    { id: "contenido3", titulo: "Introducción a CSS" },
                    { id: "contenido4", titulo: "Colores, fuentes y márgenes" }
                ]
            }
        ];

        const contenidos = {
            contenido1: "<h3>Estructura básica de HTML</h3><p>HTML se estructura con etiquetas como &lt;html&gt;, &lt;head&gt;, &lt;body&gt;, etc.</p>",
            contenido2: "<h3>Etiquetas principales</h3><p>Las etiquetas &lt;head&gt;, &lt;title&gt;, y &lt;body&gt; son esenciales en todo documento HTML.</p>",
            contenido3: "<h3>Introducción a CSS</h3><p>CSS permite aplicar estilos como colores, márgenes, y fuentes a las páginas web.</p>",
            contenido4: "<h3>Colores, fuentes y márgenes</h3><p>Con CSS puedes usar propiedades como <code>color</code>, <code>font-family</code>, <code>margin</code>, entre otras.</p>"
        };

        let subtemaActualIndex = 0;

        function toggleSubtemas(element) {
            const subtemas = element.nextElementSibling;
            subtemas.style.display = subtemas.style.display === "block" ? "none" : "block";
        }

        function abrirSubtema(index) {
            subtemaActualIndex = index;
            mostrarSubtema(subtemaActualIndex);
            document.getElementById("modalContenido").style.display = "flex";
            document.getElementById("modalContenido").focus();
        }

        function mostrarSubtema(index) {
            const subtemaGlobal = getSubtemaPorIndice(index);
            if (!subtemaGlobal) return;

            const { temaNombre, subtema } = subtemaGlobal;
            const contenidoHTML = contenidos[subtema.id];

            document.getElementById("tituloModal").textContent = `${temaNombre} - ${subtema.titulo}`;
            document.getElementById("contenidoModal").innerHTML = contenidoHTML;
            marcarComoVisto(subtema.id);

            document.getElementById("btnPrev").disabled = (index === 0);
            document.getElementById("btnNext").disabled = (index === contarSubtemas() - 1);

            actualizarBarra();
        }

        function cambiarSubtema(direccion) {
            let nuevoIndex = subtemaActualIndex + direccion;
            if (nuevoIndex < 0 || nuevoIndex >= contarSubtemas()) return;
            subtemaActualIndex = nuevoIndex;
            mostrarSubtema(subtemaActualIndex);
        }

        function cerrarModal() {
            document.getElementById("modalContenido").style.display = "none";
        }

        function marcarComoVisto(idContenido) {
            const subtemaDiv = [...document.querySelectorAll(".subtema")].find(el => el.dataset.id === idContenido);
            if (subtemaDiv && !subtemaDiv.classList.contains("visto")) {
                subtemaDiv.classList.add("visto");
                localStorage.setItem(idContenido, "true");
            }
        }

        function actualizarBarra() {
            const total = contarSubtemas();
            const vistos = [...document.querySelectorAll(".subtema.visto")].length;
            const porcentaje = Math.round((vistos / total) * 100);
            document.getElementById("barraProgreso").style.width = porcentaje + "%";
        }

        function contarSubtemas() {
            return temas.reduce((acc, tema) => acc + tema.subtemas.length, 0);
        }

        function getSubtemaPorIndice(index) {
            let contador = 0;
            for (const tema of temas) {
                for (const subtema of tema.subtemas) {
                    if (contador === index) {
                        return { temaNombre: tema.nombre, subtema };
                    }
                    contador++;
                }
            }
            return null;
        }

        window.onload = () => {
            Object.keys(localStorage).forEach(clave => {
                const subtemaDiv = [...document.querySelectorAll(".subtema")].find(el => el.dataset.id === clave);
                if (subtemaDiv) subtemaDiv.classList.add("visto");
            });
            actualizarBarra();

            document.querySelectorAll(".subtemas").forEach(div => div.style.display = "none");
        };
    </script>
</body>
</html>
