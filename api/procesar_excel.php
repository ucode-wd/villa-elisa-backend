<?php
require 'solo_admin.php';
require 'config.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_FILES['archivo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No se subió archivo']);
    exit;
}

$archivoTmp = $_FILES['archivo']['tmp_name'];
$spreadsheet = IOFactory::load($archivoTmp);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

$terreno_id = $_POST['terreno_id'] ?? null;

if (!$terreno_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta ID de terreno']);
    exit;
}

foreach ($rows as $fila) {
    // Suponemos que las columnas están así:
    // FECHA | CUOTA N° | DEBE | HABER | SALDO EN BOLSAS

    if (empty($fila[0]) || strtolower($fila[0]) == 'fecha') continue;

    $fecha = date('Y-m-d', strtotime($fila[0]));
    $cuota = is_numeric($fila[1]) ? $fila[1] : null;
    $debe = is_numeric($fila[2]) ? $fila[2] : null;
    $haber = is_numeric($fila[3]) ? $fila[3] : null;
    $saldo = is_numeric($fila[4]) ? $fila[4] : null;

    $stmt = $pdo->prepare("INSERT INTO cuotas (terreno_id, fecha, numero_cuota, debe, haber, saldo)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$terreno_id, $fecha, $cuota, $debe, $haber, $saldo]);
}

echo json_encode(['mensaje' => 'Excel procesado correctamente']);
