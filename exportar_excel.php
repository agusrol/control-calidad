<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$host = "localhost";
$db = "control_calidad";
$user = "postgres";
$pass = "admin";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $query = $pdo->query("SELECT * FROM control_calidad ORDER BY id DESC");
    $registros = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Escribir encabezados
$columnas = array_keys($registros[0]);
$col = 'A';
foreach ($columnas as $titulo) {
    $sheet->setCellValue($col . '1', $titulo);
    $col++;
}

// Escribir datos
$fila = 2;
foreach ($registros as $registro) {
    $col = 'A';
    foreach ($registro as $valor) {
        $sheet->setCellValue($col . $fila, $valor);
        $col++;
    }
    $fila++;
}

// Descargar
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="control_calidad.xlsx"');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
