<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'conexion.php';

// Consulta usuarios
$sql = "SELECT * FROM usuarios";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Panel de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Panel Administrador</a>

            <div class="d-flex">
                <a href="controladores/agregar_usuario.php" class="btn btn-success me-2">➕ Agregar Usuario</a>
                <a href="controladores/cerrar_sesion.php" class="btn btn-outline-danger">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>

        <div class="card shadow p-4 mt-3">
            <h4 class="mb-3">Usuarios del sistema</h4>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                            <td><?php echo $row['es_admin'] ? 'Administrador' : 'Alumno'; ?></td>
                            <td>
                                <a href="controladores/editar_usuario.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="controladores/eliminar_usuario.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('¿Estás seguro de eliminar este usuario?');" 
                                   class="btn btn-sm btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No hay usuarios registrados.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
