<?php
require 'proteger.php';

echo json_encode([
    'usuario_id' => $_SESSION['usuario_id'],
    'nombre' => $_SESSION['nombre'],
    'rol' => $_SESSION['rol']
]);
