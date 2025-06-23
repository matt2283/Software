<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $es_admin = isset($_POST['es_admin']) ? 1 : 0;

    $sql = "INSERT INTO usuarios (usuario, password, es_admin)
            VALUES ('$usuario', '$password', $es_admin)";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Usuario agregado correctamente'); window.location.href = '../admin_dashboard.php';</script>";
    } else {
        echo "Error al agregar usuario: " . $conexion->error;
    }

    $conexion->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-4">Agregar Nuevo Usuario</h3>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="text" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="es_admin" id="es_admin">
                <label class="form-check-label" for="es_admin">Administrador</label>
            </div>
            <button type="submit" class="btn btn-success">Agregar Usuario</button>
            <a href="../admin_dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
