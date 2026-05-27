<?php
//1. Activamos el tipado para PHP 8.x
declare(strict_types=1);

//Verificamos si los datos llegan a través del metodo POST
if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Recepcion (formulario) y conversión de tipos (cast, parse)
    //Utilizamos un operador ?? null si el dato no existe
    $componenteRecibido = $_POST["componente"] ?? '';
   
    //Convertimos el string (cantidad) en numero entero
    $cantidad = $_POST["cantidad"] ?? 0;

    //Trabajamos con el checkbox, lo convertimos en booleano
    $esInstitucional = isset($_POST["institucional"]) ? true : false;
    
    //Estructuras de control Función de PHP 8.x 'match' equivalente a un switch
    $precioUnitario = match($componenteRecibido){
        'procesador' => 350.50,
        'ram' => 85.00,
        'almacenamiento' => 120.00,
        default => 0.00,
    };

    //Operadores y expresiones
    $subTotal = $precioUnitario * $cantidad;

    //Declaramos el descuento
    $descuento = 0.0;

    //Estrutura de control para aplicar la lógica del negocio
    if($esInstitucional){
        $descuento = $subTotal * 0.10; //10% de descuento
    }
    
    //Expresion final
    $totalPagar = $subTotal - $descuento;

    // Nombres para mostrar
    $nombreComponente = match($componenteRecibido){
        'procesador' => 'Procesador Intel Core I7',
        'ram' => 'Memoria RAM 16GB DDR4',
        'almacenamiento' => 'Memoria SSD',
        default => 'No seleccionado',
    };

    // Formatear valores
    $precioF = number_format($precioUnitario, 2, ',', '.');
    $subTotalF = number_format($subTotal, 2, ',', '.');
    $descuentoF = number_format($descuento, 2, ',', '.');
    $totalF = number_format($totalPagar, 2, ',', '.');

    // Mostrar resultados
    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet'>
    <title>Resultado de Cotización</title>
</head>
<body class='bg-light'>
    <div class='container mt-5'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card shadow'>
                    <div class='card-header bg-dark text-white'>
                        <h4 class='mb-0'>Resultado de la Cotización</h4>
                    </div>
                    <div class='card-body'>
                        <p><strong>Componente:</strong> $nombreComponente</p>
                        <p><strong>Cantidad:</strong> $cantidad</p>
                        <p><strong>Precio unitario:</strong> \$$ $precioF</p>
                        <p><strong>Subtotal:</strong> \$$ $subTotalF</p>
                        <p><strong>Descuento (10%):</strong> \$$ $descuentoF</p>
                        <hr>
                        <h5 class='text-success fw-bold'>Total a Pagar: \$$ $totalF</h5>
                        <a href='index.html' class='btn btn-secondary mt-3'>Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
}