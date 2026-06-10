<?php
//1. Activamos el tipado para PHP 8.x
declare(strict_types=1);

// ⚠️ IMPORTANTE: Iniciar sesión ANTES de cualquier cosa
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

//Verificamos si los datos llegan a través del metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Recepcion (formulario) y conversión de tipos
    $componenteRecibido = $_POST["componente"] ?? '';

    //Convertimos el string (cantidad) en numero entero
    $cantidad = (int) ($_POST["cantidad"] ?? 0);

    //Validar cantidad
    if ($cantidad <= 0) {
        die("Error: La cantidad debe ser mayor a 0");
    }

    //Trabajamos con el radio button, obtenemos el valor flotante
    $porcentajeDescuento = (float) ($_POST["descuento"] ?? 0.0);
    $esInstitucional = $porcentajeDescuento > 0.0;

    //Estructuras de control Función de PHP 8.x 'match' - ACTUALIZADO con todos los componentes
    $precioUnitario = match ($componenteRecibido) {
        'procesador' => 350.50,
        'ram' => 85.00,
        'almacenamiento' => 120.00,
        'tarjeta grafica' => 350.00,
        'placa madre' => 110.00,
        'fuente de poder' => 65.00,
        'gabinete' => 55.00,
        'monitor' => 130.00,
        'refrigeracion' => 120.00,  // ✅ Agregado
        'audifonos' => 150.00,       // ✅ Agregado
        'microfono' => 100.00,       // ✅ Agregado
        'silla gamer' => 250.00,     // ✅ Agregado
        'teclado' => 80.00,          // ✅ Agregado
        default => 0.00,
    };

    //Validar componente
    if ($precioUnitario == 0.00) {
        die("Error: Componente no válido");
    }

    //Operadores y expresiones
    $subTotal = $precioUnitario * $cantidad;

    //Declaramos el descuento
    $descuento = 0.0;

    //Estructura de control para aplicar la lógica del negocio
    if ($esInstitucional) {
        $descuento = $subTotal * $porcentajeDescuento;
    }

    //Expresion final
    $totalPagar = $subTotal - $descuento;
    
    //Inserción de los datos
    try {
        require 'conexion.php';
        $stmt = $pdo->prepare("INSERT INTO cotizaciones (componente, cantidad, total) VALUES (?,?,?)");
        $stmt->execute([$componenteRecibido, $cantidad, $totalPagar]);

        //Si la inserción es exitosa, redirigimos al dashboard
        header("Location: dashboard.php?msg=guardado");
        exit();
    } catch (PDOException $e) {
        //Si MySQL falla nos muestra este error
        die("<div style='background: #ffcccc; padding: 20px; border:1px solid red; font-family: sans-serif;'>
            <h2 style='color: red;'>Error en la BDD</h2>
            <p><strong>Mensaje del servidor:</strong> " . $e->getMessage() . "</p>
        </div>");
    }

} else {
    //Si el acceso a esta pagina es directo, redirigimos al formulario
    header("Location: index.php");
    exit();
}
?>