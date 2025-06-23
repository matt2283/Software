    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Iniciar Sesión</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <form action="verificar_login.php" method="POST">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="password">Contraseña:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required>
                    <span class="mostrar-texto" id="toggleText">MOSTRAR</span>
                </div>

                <button type="submit">Ingresar</button>
            </form>
        </div>

        <script>
            const passwordInput = document.getElementById('password');
            const toggleText = document.getElementById('toggleText');

            toggleText.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                toggleText.textContent = isPassword ? 'OCULTAR' : 'MOSTRAR';
            });
        </script>
    </body>
    </html>
