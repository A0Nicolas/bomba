<?php
//Manejo de la sesion
session_start();
//SI NO está logueado, redirigir al login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
// Si está logueado, mostrar el cotizador
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Cotizador de repuestos tecnológicos</title>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Cotizador de hardware</h4>
                    </div>
                    <div class="card-body">
                        <form action="procesar.php" method="POST">
                            <div class="mb-3">
                                <label for="componente" class="form-label">Seleccione un componente:</label>
                                <select class="form-select" name="componente" id="componente" required>
                                    <option value="" disable selected>Elija una opción</option>
                                    <option value="procesador">Procesador Intel Core I7</option>
                                    <option value="ram">Memoria RAM 16GB DDR4</option>
                                    <option value="almacenamiento">Memoria SSD 1TB</option>
                                    <option value="refrigeracion">Disipador Liquido Cooler Master</option>
                                    <option value="audifonos">Audifonos HyperX Cloud Flight</option>
                                    <option value="microfono">Microfono Streamer Razer Seiren</option>
                                    <option value="silla gamer">Silla Ergonomica Corsair</option>
                                    <option value="teclado">Teclado Mecanico Redragon</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad Requerida:</label>
                                <input type="number" class="form-control" name="cantidad" id="cantidad" min="1"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Descuento Institucional:</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" id="desc_0" value="0"
                                        checked>
                                    <label class="form-check-label" for="desc_0">Ninguno (0%)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" id="desc_10"
                                        value="0.15">
                                    <label class="form-check-label" for="desc_10">15%</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" id="desc_15"
                                        value="0.20">
                                    <label class="form-check-label" for="desc_15">20%</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" id="desc_20"
                                        value="0.25">
                                    <label class="form-check-label" for="desc_20">25%</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="descuento" id="desc_25"
                                        value="0.50">
                                    <label class="form-check-label" for="desc_25">50%</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Procesar Cotización</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>