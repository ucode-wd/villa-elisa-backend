<?php
ini_set('session.gc_maxlifetime', 2592000);
session_set_cookie_params(2592000);
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}
