<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Contable - Libro Mayor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Estilos personalizados (Iguales a tu diseño original) */
        body {
            background-color: #eef2f5;
            padding: 20px;
        }

        .main-container {
            background-color: #fff;
            border: 2px solid #dc3545; /* Borde rojo */
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: auto;
            min-height: 85vh;
        }

        /* Tabla estilo Cuadrícula */
        .table-grid { border-collapse: collapse; }
        .table-grid th, .table-grid td {
            border: 1px solid #adb5bd !important;
            vertical-align: middle;
        }
        .table-thead-styled {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        /* Filas especiales */
        .saldo-row {
            font-weight: bold;
            background-color: #f1f3f5;
            color: #495057;
        }

        .btn-rounded { border-radius: 50px; padding-left: 20px; padding-right: 20px; }
        .text-currency { text-align: right; font-family: monospace; }
        
        /* Altura mínima para filas vacías */
        .empty-row td { height: 35px; }
    </style>
</head>
<body>

    <div class="container main-container mt-4">
        
        <div class="row mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h2 class="text-uppercase mb-0" style="letter-spacing: 2px;">Libro Mayor</h2>
            </div>
            <div class="col-auto">
                <a href="index.php" class="btn btn-outline-secondary btn-rounded">Volver el menú</a>
            </div>
        </div>

        <div class="row mb-4 align-items-center">
            <div class="col-md-7 d-flex align-items-center">
                <label for="seleccionCuenta" class="form-label me-3 mb-0 text-nowrap" style="font-size: 1.1rem;">Seleccione cuenta:</label>
                <select class="form-select rounded-pill" id="seleccionCuenta">
                    <option value="" selected>Cargando cuentas...</option>
                </select>
            </div>
            <div class="col-md-5 text-end mt-3 mt-md-0">
                <button type="button" class="btn btn-outline-primary btn-rounded px-4" onclick="window.print()">Imprimir</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-grid table-hover">
                <thead class="table-thead-styled text-center">
                    <tr>
                        <th scope="col" style="width: 12%;">FECHA</th>
                        <th scope="col" style="width: 43%;">DESCRIPCION</th>
                        <th scope="col" style="width: 15%;">DEBE</th>
                        <th scope="col" style="width: 15%;">HABER</th>
                        <th scope="col" style="width: 15%;">SALDO</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaMayor">
                    <tr><td colspan="5" class="text-center p-5 text-muted">Seleccione una cuenta para ver sus movimientos</td></tr>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Cargar el Select de Cuentas al iniciar
        document.addEventListener('DOMContentLoaded', () => {
            fetch('logica/PlanCuenta.php') // Reutilizamos el archivo que lista todas las cuentas
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('seleccionCuenta');
                    select.innerHTML = '<option value="" selected>Seleccionar...</option>';
                    data.forEach(c => {
                        select.innerHTML += `<option value="${c.id}">${c.nombre}</option>`;
                    });
                });
        });

        // 2. Evento al cambiar la selección (Cargar datos del Mayor)
        document.getElementById('seleccionCuenta').addEventListener('change', function() {
            const idCuenta = this.value;
            if (!idCuenta) return;

            fetch(`logica/LibroMayor.php?id_cuenta=${idCuenta}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'error') {
                        alert(data.message);
                        return;
                    }
                    renderizarMayor(data.cuenta, data.movimientos);
                })
                .catch(err => console.error(err));
        });

        // 3. Función para dibujar la tabla y calcular saldos
        function renderizarMayor(cuenta, movimientos) {
            const tbody = document.getElementById('cuerpoTablaMayor');
            tbody.innerHTML = '';

            // A. Fila de Saldo Inicial
            let saldoActual = parseFloat(cuenta.saldo_inicial);
            const saldoInicialFmt = saldoActual.toLocaleString('en-US', {minimumFractionDigits: 2});
            
            tbody.innerHTML += `
                <tr class="saldo-row">
                    <td></td>
                    <td>Saldo Inicial</td>
                    <td class="text-currency">-</td>
                    <td class="text-currency">-</td>
                    <td class="text-currency">${saldoInicialFmt}</td>
                </tr>
            `;

            // B. Filas de Movimientos
            let totalDebe = 0;
            let totalHaber = 0;

            movimientos.forEach(mov => {
                const debe = parseFloat(mov.debe);
                const haber = parseFloat(mov.haber);
                
                // --- FÓRMULA DEL SALDO ---
                // Saldo = Saldo Anterior + Debe - Haber
                saldoActual = saldoActual + debe - haber;

                // Acumuladores para totales
                totalDebe += debe;
                totalHaber += haber;

                tbody.innerHTML += `
                    <tr>
                        <td class="text-center">${mov.fecha}</td>
                        <td>${mov.glosa}</td>
                        <td class="text-currency">${debe > 0 ? debe.toLocaleString('en-US', {minimumFractionDigits: 2}) : '-'}</td>
                        <td class="text-currency">${haber > 0 ? haber.toLocaleString('en-US', {minimumFractionDigits: 2}) : '-'}</td>
                        <td class="text-currency fw-bold">${saldoActual.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                    </tr>
                `;
            });

            // C. Relleno de filas vacías (Estética de papel)
            const filasVacias = Math.max(0, 8 - movimientos.length);
            for(let i=0; i<filasVacias; i++){
                tbody.innerHTML += `<tr class="empty-row"><td></td><td></td><td></td><td></td><td></td></tr>`;
            }

            // D. Fila de Saldo Final
            tbody.innerHTML += `
                <tr class="saldo-row border-top border-2 border-secondary">
                    <td></td>
                    <td>SALDO FINAL / SUMAS</td>
                    <td class="text-currency">${totalDebe.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                    <td class="text-currency">${totalHaber.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                    <td class="text-currency">${saldoActual.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                </tr>
            `;
        }
    </script>
</body>
</html>