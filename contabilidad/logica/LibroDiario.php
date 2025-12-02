<?php
require 'db.php';
header('Content-Type: application/json');

// --- 1. OBTENER HISTORIAL (GET) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Consulta uniendo las 3 tablas para tener nombres y datos completos
        $sql = "SELECT 
                    da.id as asiento_id, 
                    da.fecha, 
                    da.glosa,
                    dd.id as detalle_id,
                    dd.folio,
                    dd.debe,
                    dd.haber,
                    pc.nombre as cuenta
                FROM diario_asientos da
                JOIN diario_detalles dd ON da.id = dd.asiento_id
                JOIN plan_cuentas pc ON dd.cuenta_id = pc.id
                ORDER BY da.fecha DESC, da.id DESC, dd.debe DESC, dd.haber ASC";
        
        $stmt = $pdo->query($sql);
        $raw_data = $stmt->fetchAll();

        // Agrupar los datos: Convertimos la lista plana en una lista jerárquica
        // Estructura: [ Asiento 1 => {detalles: [...]}, Asiento 2 => ... ]
        $asientos = [];
        foreach ($raw_data as $row) {
            $id = $row['asiento_id'];
            
            // Si es la primera vez que vemos este asiento, creamos la cabecera
            if (!isset($asientos[$id])) {
                $asientos[$id] = [
                    'id' => $id,
                    'fecha' => $row['fecha'],
                    'glosa' => $row['glosa'],
                    'movimientos' => []
                ];
            }
            
            // Agregamos la línea de detalle
            $asientos[$id]['movimientos'][] = [
                'cuenta' => $row['cuenta'],
                'folio' => $row['folio'],
                'debe' => $row['debe'],
                'haber' => $row['haber']
            ];
        }

        // Devolvemos el array reindexado (sin los IDs como claves)
        echo json_encode(array_values($asientos));

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// --- 2. GUARDAR NUEVO ASIENTO (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    try {
        $pdo->beginTransaction();

        // Insertar Cabecera
        $stmt = $pdo->prepare("INSERT INTO diario_asientos (fecha, glosa) VALUES (?, ?)");
        $stmt->execute([$input['fecha'], $input['glosa']]);
        $id_asiento = $pdo->lastInsertId();

        // Insertar Detalles
        $stmtDetalle = $pdo->prepare("INSERT INTO diario_detalles (asiento_id, cuenta_id, folio, debe, haber) VALUES (?, ?, ?, ?, ?)");

        foreach ($input['detalles'] as $linea) {
            $stmtDetalle->execute([
                $id_asiento,
                $linea['cuenta_id'],
                $linea['folio'],
                $linea['debe'],
                $linea['haber']
            ]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Asiento registrado exitosamente.']);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>