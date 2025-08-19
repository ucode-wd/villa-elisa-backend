<?php
require 'solo_admin.php';
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$terreno_id = $data['terreno_id'] ?? null;
$fecha = $data['fecha'] ?? null;
$cuota = $data['cuota'] ?? null;
$debe = $data['debe'] ?? null;
$haber = $data['haber'] ?? null;
$saldo = $data['saldo'] ?? null;

if (!$terreno_id || !$fecha) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO cuotas (terreno_id, fecha, numero_cuota, debe, haber, saldo)
                       VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$terreno_id, $fecha, $cuota, $debe, $haber, $saldo]);

echo json_encode(['mensaje' => 'Cuota cargada manualmente']);
