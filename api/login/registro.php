<?php
require '../config.php';

$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'] ?? '';
$email = $data['email'] ?? '';
$clave = $data['clave'] ?? '';
$rol = $data['rol'] ?? 'deudor'; // default

if (!$email || !$clave || !$nombre) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos']);
    exit;
}

$hash = password_hash($clave, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
$stmt->execute([$nombre, $email, $hash, $rol]);

echo json_encode(['mensaje' => 'Usuario registrado']);
