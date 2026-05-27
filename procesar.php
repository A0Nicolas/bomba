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


}