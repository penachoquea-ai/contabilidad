<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balanza de Comprobación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #eef2f5;
            padding: 20px;
        }

        /* Contenedor principal */
        .main-container {
            background-color: #fff;
            border: 2px solid #dc3545; /* Borde rojo */
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 1100px;
            margin: auto;
            min-height: 85vh;
            display: flex;
            flex-direction: column;
        }

        /* Título */
        .page-title {
            font-weight: 400;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }

        /* Tabla Cuadriculada */
        .table-responsive { flex-grow: 1; }

        .table-grid {
            border: 2px solid #adb5bd;
            border-radius: 15px; /* Bordes redondeados externos */
            border-collapse: separate; 
            border-spacing: 0;
            width: 100%;
            overflow: hidden;
        }

        .table-grid th {
            background-color: #f1f3f5;
            border: 1px solid #adb5bd;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 12px;
            text-align: center;
        }

        .table-grid td {
            border: 1px solid #ced4da;
            padding: 8px;
            vertical-align: middle;
        }

        /* Columna de Totales */
        .row-totales td {
            background-color: #e9ecef;
            font-weight: bold;
            border-top: 2px solid #6c757d;
            font-size: 1rem;
        }

        .text-currency {
            text-align: right;
            font-family: 'Courier New', Courier, monospace;
        }

        /* Botones inferiores */
        .btn-custom {
            border-radius: 50px;
            padding: 8px 25px;
            border: 1px solid #6c757d;
            color: #6c757d;
            background: white;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-custom:hover {
            background-color: #6c757d;
            color: white;
        }
        .footer-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <div class="container main-container">
        
        <h2 class="page-title text-center text-md-start">
            Balanza de Comprobación de Sumas y Saldos
        </h2>

        <div class="table-responsive">
            <table class="table table-grid mb-0 table-hover">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle;">CUENTA</th>
                        <th colspan="2">SUMAS</th>
                        <th colspan="2">SALDOS</th>
                    </tr>
                    <tr>
                        <th>DEBE</th>
                        <th>HABER</th>
                        <th>DEUDOR</th>
                        <th>ACREEDOR</th>
                    </tr>
                </thead>
                <tbody id="cuerpoBalanza">
                    <tr><td colspan="5" class="text-center p-4">Cargando datos...</td></tr>
                </tbody>
                
                <tfoot>
                    <tr class="row-totales" id="filaTotales">
                        <td class="ps-4">TOTALES</td>
                        <td class="text-currency">0.00</td> <td class="text-currency">0.00</td> <td class="text-currency">0.00</td> <td class="text-currency">0.00</td> </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer-actions">
            <a href="index.php" class="btn btn-custom">
                <span>&larr;</span> Volver al menú
            </a>
            <button class="btn btn-custom" onclick="window.print()">
                Imprimir
            </button>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarBalanza();
        });

        function cargarBalanza() {
            fetch('logica/BalanceComprobacion.php')
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'error') {
                        alert(response.message);
                        return;
                    }
                    renderizarTabla(response.data);
                })
                .catch(err => console.error(err));
        }

        function renderizarTabla(cuentas) {
            const tbody = document.getElementById('cuerpoBalanza');
            tbody.innerHTML = '';

            let totalSumaDebe = 0;
            let totalSumaHaber = 0;
            let totalSaldoDeudor = 0;
            let totalSaldoAcreedor = 0;

            cuentas.forEach(c => {
                // Convertir a números
                const saldoInicial = parseFloat(c.saldo_inicial);
                const sumaDebe = parseFloat(c.suma_debe);
                const sumaHaber = parseFloat(c.suma_haber);

                // --- LÓGICA DE SALDOS ---
                // Saldo Final = Inicial + Entradas (Debe) - Salidas (Haber)
                // Nota: Esto asume cuentas de naturaleza Deudora (Activo/Gasto). 
                // Si la cuenta es de Pasivo/Ingreso, el resultado será negativo, lo cual es correcto para la lógica.
                
                // Calculamos el saldo matemático
                /* ATENCIÓN: Para simplificar, asumiremos:
                   Saldo Neto = Saldo Inicial + Debe - Haber.
                   Si es positivo -> Columna Deudor.
                   Si es negativo -> Columna Acreedor (Valor absoluto).
                */
                const saldoNeto = saldoInicial + sumaDebe - sumaHaber;

                let mostrarDeudor = 0;
                let mostrarAcreedor = 0;

                if (saldoNeto >= 0) {
                    mostrarDeudor = saldoNeto;
                } else {
                    mostrarAcreedor = Math.abs(saldoNeto);
                }

                // Acumular Totales Generales
                totalSumaDebe += sumaDebe;
                totalSumaHaber += sumaHaber;
                totalSaldoDeudor += mostrarDeudor;
                totalSaldoAcreedor += mostrarAcreedor;

                // Renderizar Fila
                // Solo mostramos cuentas que tengan movimiento o saldo
                if(sumaDebe === 0 && sumaHaber === 0 && saldoInicial === 0) return;

                const fila = `
                    <tr>
                        <td class="ps-3 text-start">${c.nombre}</td>
                        <td class="text-currency text-secondary">${sumaDebe > 0 ? sumaDebe.toFixed(2) : '-'}</td>
                        <td class="text-currency text-secondary">${sumaHaber > 0 ? sumaHaber.toFixed(2) : '-'}</td>
                        <td class="text-currency fw-bold text-dark">${mostrarDeudor > 0 ? mostrarDeudor.toFixed(2) : '-'}</td>
                        <td class="text-currency fw-bold text-dark">${mostrarAcreedor > 0 ? mostrarAcreedor.toFixed(2) : '-'}</td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });

            // Rellenar filas vacías para estética
            const filasActuales = tbody.children.length;
            for(let i=0; i < (10 - filasActuales); i++){
                tbody.innerHTML += `<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>`;
            }

            // Actualizar Totales Finales
            const filaTotales = document.getElementById('filaTotales');
            filaTotales.innerHTML = `
                <td class="ps-4">TOTALES</td>
                <td class="text-currency">${totalSumaDebe.toFixed(2)}</td>
                <td class="text-currency">${totalSumaHaber.toFixed(2)}</td>
                <td class="text-currency">${totalSaldoDeudor.toFixed(2)}</td>
                <td class="text-currency">${totalSaldoAcreedor.toFixed(2)}</td>
            `;

            // Validación Visual: ¿Cuadra el balance?
            if (Math.abs(totalSaldoDeudor - totalSaldoAcreedor) < 0.01) {
                filaTotales.classList.add('table-success'); // Verde si cuadra
            } else {
                filaTotales.classList.add('table-danger'); // Rojo si no cuadra
            }
        }
    </script>
</body>
</html>