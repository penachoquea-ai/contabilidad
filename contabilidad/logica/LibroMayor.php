<?php
require 'db.php';
header('Content-Type: application/json');

// Si no nos envían un ID, devolvemos error
if (!isset($_GET['id_cuenta'])) {
    echo json_encode(['status' => 'error', 'message' => 'Falta el ID de la cuenta']);
    exit;
}

$id_cuenta = $_GET['id_cuenta'];

try {
    // 1. Obtener Datos de la Cuenta (Saldo Inicial)
    $stmtCuenta = $pdo->prepare("SELECT nombre, saldo_inicial, tipo FROM plan_cuentas WHERE id = ?");
    $stmtCuenta->execute([$id_cuenta]);
    $cuenta = $stmtCuenta->fetch();

    if (!$cuenta) {
        echo json_encode(['status' => 'error', 'message' => 'Cuenta no encontrada']);
        exit;
    }

    // 2. Obtener Movimientos del Libro Diario para esta cuenta
    $sqlMovimientos = "SELECT 
                            da.fecha, 
                            da.glosa, 
                            dd.debe, 
                            dd.haber 
                       FROM diario_detalles dd
                       JOIN diario_asientos da ON dd.asiento_id = da.id
                       WHERE dd.cuenta_id = ?
                       ORDER BY da.fecha ASC, da.id ASC";
    
    $stmtMov = $pdo->prepare($sqlMovimientos);
    $stmtMov->execute([$id_cuenta]);
    $movimientos = $stmtMov->fetchAll();

    // 3. Devolver todo junto
    echo json_encode([
        'status' => 'success',
        'cuenta' => $cuenta,
        'movimientos' => $movimientos
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>