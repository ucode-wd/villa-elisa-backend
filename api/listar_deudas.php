<?php
// /api/cuotas/listar.php

session_start();
header('Content-Type: application/json');

require_once 'config.php'; 

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "No autorizado"]);
    http_response_code(401);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol']; // 'admin' o 'deudor'

try {
    if ($rol === 'admin') {
        // Admin: ve todos los movimientos
        $sql = "SELECT 
                    m.id,
                    u.nombre AS deudor,
                    t.descripcion AS terreno,
                    t.ubicacion,
                    m.fecha,
                    m.cuota_nro,
                    m.debe,
                    m.haber,
                    m.saldo
                FROM movimientos m
                JOIN terrenos t ON m.terreno_id = t.id
                JOIN usuarios u ON t.usuario_id = u.id
                ORDER BY m.fecha ASC";
        $stmt = $pdo->query($sql);

    } else {
        // Deudor: ve solo sus terrenos
        $sql = "SELECT 
                    m.id,
                    t.descripcion AS terreno,
                    t.ubicacion,
                    m.fecha,
                    m.cuota_nro,
                    m.debe,
                    m.haber,
                    m.saldo
                FROM movimientos m
                JOIN terrenos t ON m.terreno_id = t.id
                WHERE t.usuario_id = :usuario_id
                ORDER BY m.fecha ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id]);
    }

    $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($movimientos);

} catch (PDOException $e) {
    echo json_encode(["error" => "Error al obtener cuotas: " . $e->getMessage()]);
    http_response_code(500);
}
