<?php
include '../conexion.php';

if (!isset($_GET['id'])) {
    echo "ID de usuario no proporcionado.";
    exit();
}

$id = $_GET['id'];

// Si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $es_admin = isset($_POST['es_admin']) ? 1 : 0;

    $sql = "UPDATE usuarios SET usuario = '$usuario', password = '$password', es_admin = $es_admin WHERE id = $id";
    
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Usuario actualizado correctamente'); window.location.href = '../admin_dashboard.php';</script>";
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }

    $conexion->close();
    exit();
}

// Obtener datos actuales del usuario
$sql = "SELECT * FROM usuarios WHERE id = $id";
$resultado = $conexion->query($sql);

if ($resultado->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario_data = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-4">Editar Usuario</h3>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" class="form-control" value="<?php echo htmlspecialchars($usuario_data['usuario']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="text" name="password" class="form-control" value="<?php echo htmlspecialchars($usuario_data['password']); ?>" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="es_admin" id="es_admin" <?php if ($usuario_data['es_admin']) echo 'checked'; ?>>
                <label class="form-check-label" for="es_admin">Administrador</label>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="../admin_dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
