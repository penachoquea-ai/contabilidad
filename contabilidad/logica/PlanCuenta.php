<?php
require 'db.php'; // Usamos la conexión
header('Content-Type: application/json');

// 1. LISTAR CUENTAS (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM plan_cuentas ORDER BY id DESC");
        $cuentas = $stmt->fetchAll();
        echo json_encode($cuentas);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// 2. GUARDAR CUENTA (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leemos el JSON que viene del formulario
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre']) || !isset($data['tipo'])) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        exit;
    }

    try {
        $sql = "INSERT INTO plan_cuentas (nombre, tipo, saldo_inicial) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nombre'], 
            $data['tipo'], 
            $data['saldo'] ?? 0 // Si no envían saldo, ponemos 0
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Cuenta guardada correctamente']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar: ' . $e->getMessage()]);
    }
    exit;
}
?>