<?php
//tipado estricto
declare(strict_types=1);
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require 'conexion.php';
//creamos un funcion que  me permita general la descarga del archivo con un diferente nombre 
function generarNombreArchivo(string $prefijo, string $extension): string
{
    $fecha = date('Ymd_His');
    $bytesAleatorios=random_bytes(4);
    $sufijoAleatorio=bin2hex($bytesAleatorios);
    return "{$prefijo}_{$fecha}_($sufijoAleatorio)_.{$extension}";
}

//capturamos el tipo de reporte 
$tipo=$_GET['tipo'] ?? '';
if ($tipo === 'excel') {
    try{
        $stmt = $pdo->query("SELECT id,componente,cantidad,total,fecha FROM cotizaciones ORDER BY id DESC");
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //LLamar a la funcion que genera el archivo
        $nombreArchivo=generarNombreArchivo('reporte_cotizaciones','csv');

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$nombreArchivo}");
        header('Pragma: no-cache');
        header('Expires: 0');

        $flujo = fopen('php://output', 'w');
        fputs($flujo, "\xEF\xBB\xBF");
        fputcsv($flujo, ['ID', 'Componente', 'Cantidad', 'Total', 'Fecha'], ';');

        foreach ($registros as $fila) {
            fputcsv($flujo,[
                $fila['id'],
                $fila['componente'],
                $fila['cantidad'],
                number_format((float)$fila['total'], 2, '.', ''),
                $fila['fecha']
            ], ';');
        }
        fclose($flujo);
        exit();
    }
    catch(PDOException $e){
        die("Error al generar el reporte Excel:  " . $e->getMessage());
    }
}