<?php
//1. Activamos el tipado para PHP 8.x
declare(strict_types=1);

//Verificamos si los datos llegan a través del metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Recepcion (formulario) y conversión de tipos (cast, parse)
    //Utilizamos un operador ?? null si el dato no existe
    $componenteRecibido = $_POST["componente"] ?? '';

    //Convertimos el string (cantidad) en numero entero
    $cantidad = $_POST["cantidad"] ?? 0;

    //Trabajamos con el radio button, obtenemos el valor flotante
    $porcentajeDescuento = (float) ($_POST["descuento"] ?? 0.0);
    $esInstitucional = $porcentajeDescuento > 0.0;

    //Estructuras de control Función de PHP 8.x 'match' equivalente a un switch
    $precioUnitario = match ($componenteRecibido) {
        'procesador' => 350.50,
        'ram' => 85.00,
        'almacenamiento' => 120.00,
        'tarjeta grafica' => 350.00,
        'placa madre' => 110.00,
        'fuente de poder' => 65.00,
        'gabinete' => 55.00,
        'monitor' => 130.00,
        default => 0.00,
    };

    //Operadores y expresiones
    $subTotal = $precioUnitario * $cantidad;

    //Declaramos el descuento
    $descuento = 0.0;

    //Estrutura de control para aplicar la lógica del negocio
    if ($esInstitucional) {
        $descuento = $subTotal * $porcentajeDescuento;
    }

    //Expresion final
    $totalPagar = $subTotal - $descuento;

    //Salida de los datos, Mezclar PHP con HTML
    echo "<!DOCTYPE HTML><html lang='es'><head><meta charset='UTF-8'>";
    echo "<title>Resultado de la Cotización</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "</head><body class ='bg-light'><div class='container mt-5'><div class='row'>";
    echo "<div class='alert alert-success shadow'>";
    echo "<h2 class='alert-heading'>Resumen de su pedido</h2>";
    echo "<hr>";
    echo "<p><strong>Componente:</strong> " . htmlspecialchars($componenteRecibido) . "</p>";
    echo "<p><strong>Cantidad:</strong> " . htmlspecialchars((string)$cantidad) . " unidades</p>";
    echo "<p><strong>Precio Unitario:</strong> $" . number_format($precioUnitario, 2) . "</p>";
    echo "<p><strong>Sub Total:</strong> $" . number_format($subTotal, 2) . "</p>";
    if ($esInstitucional == true) {
        echo "<p class='text-danger'> <strong>Descuento Institucional con el " . ($porcentajeDescuento * 100) . "%:</strong> $" . number_format($descuento, 2) . "</p>";
        echo "<h3> <strong>Total a pagar:</strong> $" . number_format($totalPagar, 2) . "</h3>";
        echo "<a href='index.html' class='btn btn-outline-success mt-3'>Realizar otra cotizacion</a>";
        echo "</div></div></body></html>";
    } else {
        echo "<h3> <strong>Total a pagar:</strong> $" . number_format($totalPagar, 2) . "</h3>";
        echo "<a href='index.html' class='btn btn-outline-success mt-3'>Realizar otra cotizacion</a>";
        echo "</div></div></body></html>";
    }
}