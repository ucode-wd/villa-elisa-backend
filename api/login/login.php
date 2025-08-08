<?php
require '../config.php';

// Sesión por 30 días
ini_set('session.gc_maxlifetime', 2592000);
session_set_cookie_params(2592000);
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$clave = $data['clave'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && password_verify($clave, $usuario['clave'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['nombre'] = $usuario['nombre'];

    echo json_encode([
        'mensaje' => 'Inicio exitoso',
        'rol' => $usuario['rol'],
        'nombre' => $usuario['nombre']
    ]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Credenciales inválidas']);
}
