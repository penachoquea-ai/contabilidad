<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Contable - Plan de Cuentas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #eef2f5;
            padding: 20px;
        }

        /* Contenedor principal (Hoja de papel con borde rojo) */
        .main-container {
            background-color: #fff;
            border: 2px solid #dc3545; 
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: auto;
            min-height: 80vh;
        }

        /* Caja de Gestión (Superior) */
        .management-box {
            border: 2px solid #adb5bd;
            border-radius: 20px;
            padding: 20px;
            background-color: #fdfdfd;
            margin-bottom: 20px;
        }

        /* Etiquetas estilo "píldora" */
        .label-pill {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 50px;
            padding: 5px 15px;
            font-weight: 500;
            color: #555;
            white-space: nowrap;
            display: inline-block;
            min-width: 140px;
            text-align: center;
        }

        /* Inputs y Selects redondeados */
        .form-control.rounded-pill, .form-select.rounded-pill {
            padding-left: 20px;
        }

        /* Tabla con estilo de cuadrícula */
        .table-container {
            border: 2px solid #adb5bd;
            border-radius: 20px;
            padding: 15px;
            height: 100%;
        }
        .table-grid th, .table-grid td {
            border: 1px solid #ced4da;
            vertical-align: middle;
        }

        /* Botones */
        .btn-rounded {
            border-radius: 50px;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container main-container">
        
        <div class="row mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h2 class="text-uppercase">PLAN DE CUENTA</h2>
            </div>
            <div class="col-auto">
                <a href="index.php" class="btn btn-outline-secondary btn-rounded" style="width: auto;">Volver al menú</a>
            </div>
        </div>

        <div class="management-box">
            <h5 class="ms-2 text-secondary">Gestión de cuentas</h5>
            
            <div class="row align-items-center">
                <div class="col-md-8">
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="label-pill me-3">Nombre de Cuenta</div>
                        <input type="text" id="nombreCuenta" class="form-control rounded-pill" placeholder="Ej: Caja Chica">
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="label-pill me-3">Saldo Inicial</div>
                        <input type="number" id="saldoInicial" class="form-control rounded-pill" placeholder="0.00" step="0.01">
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="label-pill me-3">Tipo de cuenta</div>
                        <select id="tipoCuenta" class="form-select rounded-pill">
                            <option value="" selected>Seleccionar...</option>
                            <option value="Activo">Activo</option>
                            <option value="Pasivo">Pasivo</option>
                            <option value="Patrimonio">Patrimonio</option>
                            <option value="Ingreso">Ingreso</option>
                            <option value="Gasto">Gasto</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 offset-md-1 d-flex flex-column justify-content-center">
                    <button type="button" id="btnAgregar" class="btn btn-outline-success btn-rounded">Agregar cuenta</button>
                    <button type="button" class="btn btn-outline-primary btn-rounded">Modificar cuenta</button>
                    <button type="button" class="btn btn-outline-danger btn-rounded">Eliminar cuenta</button>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-9">
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-grid table-hover text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase w-50 text-start ps-4">CUENTAS</th>
                                    <th class="text-uppercase w-25">SALDO INICIAL</th>
                                    <th class="text-uppercase w-25">TIPO</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCuentasBody">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-center gap-3">
                <button class="btn btn-outline-secondary btn-rounded py-2" onclick="limpiarCampos()">Limpiar campos</button>
                <button class="btn btn-outline-dark btn-rounded py-2">Imprimir</button>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Cargar la tabla cuando se abre la página
        document.addEventListener('DOMContentLoaded', function() {
            cargarTabla();
        });

        // 2. Función para guardar una nueva cuenta
        document.getElementById('btnAgregar').addEventListener('click', function() {
            // Obtener valores
            const nombre = document.getElementById('nombreCuenta').value;
            const saldo = document.getElementById('saldoInicial').value;
            const tipo = document.getElementById('tipoCuenta').value;

            // Validaciones simples
            if (nombre.trim() === '') {
                alert('Por favor, ingrese el nombre de la cuenta.');
                return;
            }
            if (tipo === '') {
                alert('Por favor, seleccione un tipo de cuenta.');
                return;
            }

            // Preparar datos para enviar
            const datos = {
                nombre: nombre,
                saldo: saldo === '' ? 0 : saldo, // Si está vacío, enviar 0
                tipo: tipo
            };

            // Enviar a PHP (Fetch API)
            fetch('logica/PlanCuenta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    limpiarCampos(); // Limpiar inputs
                    cargarTabla();   // Recargar la lista visualmente
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // 3. Función para cargar la tabla desde la Base de Datos
        function cargarTabla() {
            fetch('logica/PlanCuenta.php') // GET por defecto
            .then(response => response.json())
            .then(cuentas => {
                const tbody = document.getElementById('tablaCuentasBody');
                tbody.innerHTML = ''; // Limpiar contenido actual

                if (cuentas.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3">No hay cuentas registradas</td></tr>';
                    return;
                }

                // Dibujar filas
                cuentas.forEach(c => {
                    // Formatear número a moneda (ej: 10,000.00)
                    const saldoFormateado = parseFloat(c.saldo_inicial).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    
                    const fila = `
                        <tr>
                            <td class="text-start ps-4">${c.nombre}</td>
                            <td class="text-end pe-3">${saldoFormateado}</td>
                            <td><span class="badge bg-light text-dark border">${c.tipo}</span></td>
                        </tr>
                    `;
                    tbody.innerHTML += fila;
                });

                // Rellenar filas vacías para mantener estética si hay pocas cuentas
                const filasVacias = 8 - cuentas.length;
                for(let i=0; i<filasVacias; i++){
                   tbody.innerHTML += '<tr><td>&nbsp;</td><td></td><td></td></tr>';
                }
            })
            .catch(error => console.error('Error cargando tabla:', error));
        }

        // 4. Función auxiliar para limpiar inputs
        function limpiarCampos() {
            document.getElementById('nombreCuenta').value = '';
            document.getElementById('saldoInicial').value = '';
            document.getElementById('tipoCuenta').value = '';
        }
    </script>
</body>
</html>