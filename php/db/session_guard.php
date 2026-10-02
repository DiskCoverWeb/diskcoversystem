<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION['INGRESO'])
    || !is_array($_SESSION['INGRESO'])
    || !isset($_SESSION['INGRESO']['IDEntidad'], $_SESSION['INGRESO']['item'])
) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Sesión no activa']);
    exit;
}
