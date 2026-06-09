<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require 'conexion.php';

$error = '';
$exito = '';

//Obtener el ID de la cotización a editar
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

//Obtener los datos actuales
$stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE id = ?");
$stmt->execute([$id]);
$cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cotizacion) {
    header("Location: dashboard.php");
    exit;
}

//Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $componente = $_POST['componente'] ?? '';
    $cantidad = (int) ($_POST['cantidad'] ?? 0);
    $descuentoPorcentaje = (float) ($_POST['descuento'] ?? 0);
    
    //Precios de los componentes
    $precioUnitario = match ($componente) {
        'procesador' => 350.50,
        'ram' => 85.00,
        'almacenamiento' => 120.00,
        'tarjeta grafica' => 350.00,
        'placa madre' => 110.00,
        'fuente de poder' => 65.00,
        'gabinete' => 55.00,
        'monitor' => 130.00,
        'refrigeracion' => 120.00,
        'audifonos' => 150.00,
        'microfono' => 100.00,
        'silla gamer' => 250.00,
        'teclado' => 80.00,
        default => 0.00,
    };
    
    if ($precioUnitario == 0.00 || $cantidad <= 0) {
        $error = "Datos no válidos";
    } else {
        $subTotal = $precioUnitario * $cantidad;
        $descuento = $subTotal * $descuentoPorcentaje;
        $totalPagar = $subTotal - $descuento;
        
        //Actualizar en la base de datos
        $stmt = $pdo->prepare("UPDATE cotizaciones SET componente = ?, cantidad = ?, total = ? WHERE id = ?");
        if ($stmt->execute([$componente, $cantidad, $totalPagar, $id])) {
            header("Location: dashboard.php?msg=editado");
            exit;
        } else {
            $error = "Error al actualizar el registro";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Cotización</title>
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="">Editar Cotización</a>
            <div class="d-flex align-items-center">
                <a href="dashboard.php" class="btn btn-secondary btn-sm">← Volver al Dashboard</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">Editando cotización #<?= $id ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Componente:</label>
                                <select class="form-select" name="componente" required>
                                    <option value="procesador" <?= $cotizacion['componente'] == 'procesador' ? 'selected' : '' ?>>Procesador Intel Core I7 - $350.50</option>
                                    <option value="ram" <?= $cotizacion['componente'] == 'ram' ? 'selected' : '' ?>>Memoria RAM 16GB DDR4 - $85.00</option>
                                    <option value="almacenamiento" <?= $cotizacion['componente'] == 'almacenamiento' ? 'selected' : '' ?>>Memoria SSD 1TB - $120.00</option>
                                    <option value="tarjeta grafica" <?= $cotizacion['componente'] == 'tarjeta grafica' ? 'selected' : '' ?>>Tarjeta Gráfica RTX 3060 - $350.00</option>
                                    <option value="placa madre" <?= $cotizacion['componente'] == 'placa madre' ? 'selected' : '' ?>>Placa Madre B450 - $110.00</option>
                                    <option value="fuente de poder" <?= $cotizacion['componente'] == 'fuente de poder' ? 'selected' : '' ?>>Fuente de Poder 650W - $65.00</option>
                                    <option value="gabinete" <?= $cotizacion['componente'] == 'gabinete' ? 'selected' : '' ?>>Gabinete Gamer - $55.00</option>
                                    <option value="monitor" <?= $cotizacion['componente'] == 'monitor' ? 'selected' : '' ?>>Monitor 24" - $130.00</option>
                                    <option value="refrigeracion" <?= $cotizacion['componente'] == 'refrigeracion' ? 'selected' : '' ?>>Disipador Liquido Cooler Master - $120.00</option>
                                    <option value="audifonos" <?= $cotizacion['componente'] == 'audifonos' ? 'selected' : '' ?>>Audifonos HyperX Cloud Flight - $150.00</option>
                                    <option value="microfono" <?= $cotizacion['componente'] == 'microfono' ? 'selected' : '' ?>>Microfono Streamer Razer Seiren - $100.00</option>
                                    <option value="silla gamer" <?= $cotizacion['componente'] == 'silla gamer' ? 'selected' : '' ?>>Silla Ergonomica Corsair - $250.00</option>
                                    <option value="teclado" <?= $cotizacion['componente'] == 'teclado' ? 'selected' : '' ?>>Teclado Mecanico Redragon - $80.00</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Cantidad:</label>
                                <input type="number" class="form-control" name="cantidad" min="1" value="<?= $cotizacion['cantidad'] ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label d-block">Descuento Institucional:</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" value="0" <?= $cotizacion['total'] / (precioUnitarioOriginal($cotizacion['componente']) * $cotizacion['cantidad']) > 0.99 ? 'checked' : '' ?>>
                                    <label class="form-check-label">Ninguno (0%)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" value="0.15">
                                    <label class="form-check-label">15%</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" value="0.20">
                                    <label class="form-check-label">20%</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" value="0.25">
                                    <label class="form-check-label">25%</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" value="0.50">
                                    <label class="form-check-label">50%</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-warning w-100">Actualizar Cotización</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
//Función auxiliar para obtener precio unitario original
function precioUnitarioOriginal($componente) {
    return match ($componente) {
        'procesador' => 350.50,
        'ram' => 85.00,
        'almacenamiento' => 120.00,
        'tarjeta grafica' => 350.00,
        'placa madre' => 110.00,
        'fuente de poder' => 65.00,
        'gabinete' => 55.00,
        'monitor' => 130.00,
        'refrigeracion' => 120.00,
        'audifonos' => 150.00,
        'microfono' => 100.00,
        'silla gamer' => 250.00,
        'teclado' => 80.00,
        default => 0.00,
    };
}
?>