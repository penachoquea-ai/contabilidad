<?php
require 'db.php';
header('Content-Type: application/json');

try {
    // Consulta Maestra:
    // 1. Trae todas las cuentas del plan.
    // 2. Suma todos los 'debe' y 'haber' del diario_detalles.
    // 3. Agrupa por cuenta.
    
    $sql = "SELECT 
                p.id,
                p.nombre,
                p.saldo_inicial,
                COALESCE(SUM(dd.debe), 0) as suma_debe,
                COALESCE(SUM(dd.haber), 0) as suma_haber
            FROM plan_cuentas p
            LEFT JOIN diario_detalles dd ON p.id = dd.cuenta_id
            GROUP BY p.id, p.nombre, p.saldo_inicial
            ORDER BY p.id ASC";

    $stmt = $pdo->query($sql);
    $cuentas = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $cuentas]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>