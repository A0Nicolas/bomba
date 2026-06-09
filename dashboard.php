<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require 'conexion.php';

//Lógica para eliminar
if (isset($_GET['eliminar'])) {
    $idEliminar = (int) $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM cotizaciones WHERE id=?");
    $stmt->execute([$idEliminar]);
    header("Location: dashboard.php?msg=eliminado");
    exit;
}

//Lógica para leer
$stmt = $pdo->query("SELECT * FROM cotizaciones ORDER BY id DESC");
$cotizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="">Panel de Administración</a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3">Usuario: <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="index.php" class="btn btn-primary btn-sm me-2">Volver a Cotizar</a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Registro de cotizaciones</h2>
        
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'eliminado'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Registro eliminado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] == 'editado'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Registro actualizado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] == 'guardado'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Cotización guardada correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Componente</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cotizaciones as $cot): ?>
                            <tr>
                                <td><?= $cot['id'] ?></td>
                                <td><?= htmlspecialchars($cot['componente']) ?></td>
                                <td><?= $cot['cantidad'] ?></td>
                                <td>$<?= number_format($cot['total'], 2) ?></td>
                                <td><?= $cot['fecha'] ?></td>
                                <td>
                                    <a href="editar.php?id=<?= $cot['id'] ?>" class="btn btn-warning btn-sm">
                                        ✏️ Editar
                                    </a>
                                    <a href="dashboard.php?eliminar=<?= $cot['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro que desea eliminar el registro?');">
                                        🗑️ Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($cotizaciones)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay cotizaciones registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>