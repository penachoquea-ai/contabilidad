<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Contable - Libro Diario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #eef2f5;
            padding: 20px;
        }

        /* Contenedor principal (Borde rojo) */
        .main-container {
            background-color: #fff;
            border: 2px solid #dc3545;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: auto;
            min-height: 90vh;
        }

        /* Cajas de secciones */
        .section-box {
            border: 1px solid #adb5bd;
            border-radius: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            height: 100%;
        }

        .section-title {
            color: #6c757d;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }

        /* Botones y Estilos */
        .btn-rounded { border-radius: 50px; }
        
        /* Tabla Cuadriculada */
        .table-grid th, .table-grid td {
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }
        .table-thead-styled { background-color: #e9ecef; }
        
        /* Totales */
        .totals-display {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>
<body>

    <div class="container main-container">
        
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="text-uppercase text-secondary">Libro Diario</h2>
            </div>
            <div class="col-auto">
                <a href="index.php" class="btn btn-outline-secondary btn-rounded">Volver al Menú</a>
            </div>
        </div>

        <div class="row mb-4">
            
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="section-box">
                    <h5 class="section-title">Nuevo Asiento Contable</h5>
                    
                    <div class="mb-3">
                        <label for="fechaAsiento" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fechaAsiento">
                    </div>
                    
                    <div class="mb-3">
                        <label for="glosaAsiento" class="form-label">Glosa / Descripción</label>
                        <textarea class="form-control" id="glosaAsiento" rows="4" placeholder="Describa la transacción..."></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="section-box">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-2">
                        <h5 class="m-0 text-secondary">Línea de detalle</h5>
                        <div>
                            <button class="btn btn-sm btn-success btn-rounded me-2" id="btnGuardarAsiento">Guardar Asiento</button>
                            <button class="btn btn-sm btn-outline-warning btn-rounded" onclick="limpiarAsiento()">Limpiar</button>
                        </div>
                    </div>

                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-md-5">
                            <label class="form-label small">Cuenta</label>
                            <select class="form-select" id="selectCuenta">
                                <option value="" selected>Cargando cuentas...</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Folio</label>
                            <input type="number" class="form-control" id="inputFolio" placeholder="Ref">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Debe</label>
                            <input type="number" class="form-control" id="inputDebe" placeholder="0.00">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Haber</label>
                            <input type="number" class="form-control" id="inputHaber" placeholder="0.00">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-primary w-100 fw-bold" id="btnAgregarLinea" title="Añadir">+</button>
                        </div>
                    </div>
                    
                    <div class="alert alert-light border text-center small text-muted">
                        Agregue líneas usando el botón (+) antes de guardar el asiento.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered table-grid table-hover text-center">
                        <thead class="table-thead-styled">
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Cuenta / Detalle</th>
                                <th width="10%">Folio</th>
                                <th width="15%">Debe</th>
                                <th width="15%">Haber</th>
                                <th width="5%">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDetalleBody">
                            </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="3" class="text-end">TOTALES DEL ASIENTO:</td>
                                <td class="text-end" id="totalDebeDisplay">0.00</td>
                                <td class="text-end" id="totalHaberDisplay">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div id="mensajeCuadre" class="mt-2 fw-bold text-danger small"></div>
            </div>

            <div class="col-md-2 d-flex flex-column gap-3 justify-content-start mt-4 mt-md-0">
                <button class="btn btn-outline-dark btn-rounded py-2">Imprimir Libro</button>
                <button class="btn btn-outline-danger btn-rounded py-2">Eliminar último</button>
            </div>
        </div>
    </div>

    <hr class="my-5 border-danger border-2 opacity-50">

        <div class="row">
            <div class="col-12">
                <h4 class="text-secondary text-uppercase mb-3">Historial de Registros</h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-grid table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="10%">Fecha</th>
                                <th width="50%">Descripción / Cuentas</th>
                                <th width="10%">Folio</th>
                                <th width="15%">Debe</th>
                                <th width="15%">Haber</th>
                            </tr>
                        </thead>
                        <tbody id="tablaHistorialBody">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variables Globales
        let detallesAsiento = [];
        let totalDebe = 0;
        let totalHaber = 0;

        // 1. Al Cargar la página: Llenar el Select de Cuentas
        document.addEventListener('DOMContentLoaded', () => {
            fetch('logica/PlanCuenta.php') // Usamos el mismo archivo que lista cuentas
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('selectCuenta');
                    select.innerHTML = '<option value="" selected>Seleccionar cuenta...</option>';
                    data.forEach(cuenta => {
                        select.innerHTML += `<option value="${cuenta.id}">${cuenta.nombre}</option>`;
                    });
                    renderizarTabla(); // Dibujar tabla vacía inicial
                })
                .catch(err => console.error("Error cargando cuentas:", err));
        });

        // 2. Botón (+): Agregar línea al array temporal
        document.getElementById('btnAgregarLinea').addEventListener('click', () => {
            const select = document.getElementById('selectCuenta');
            const folio = document.getElementById('inputFolio').value;
            const debe = parseFloat(document.getElementById('inputDebe').value) || 0;
            const haber = parseFloat(document.getElementById('inputHaber').value) || 0;

            // Validaciones
            if (select.value === "") {
                alert("Debe seleccionar una cuenta.");
                return;
            }
            if (debe === 0 && haber === 0) {
                alert("Debe ingresar un monto en Debe o Haber.");
                return;
            }

            // Agregar al array
            detallesAsiento.push({
                cuenta_id: select.value,
                nombre_cuenta: select.options[select.selectedIndex].text,
                folio: folio,
                debe: debe,
                haber: haber
            });

            // Limpiar inputs
            document.getElementById('inputDebe').value = '';
            document.getElementById('inputHaber').value = '';
            document.getElementById('selectCuenta').value = '';
            document.getElementById('selectCuenta').focus();

            renderizarTabla();
        });

        /*prueba  */

        // --- NUEVO CÓDIGO A AÑADIR ---

    // 1. Cargar el historial al iniciar la página
    document.addEventListener('DOMContentLoaded', () => {
        // ... (Tu código existente que carga el select de cuentas) ...
        
        cargarHistorial(); // <--- LLAMADA NUEVA
    });

    // 2. Función para obtener y dibujar el historial
    function cargarHistorial() {
        fetch('logica/LibroDiario.php') // GET
        .then(res => res.json())
        .then(asientos => {
            const tbody = document.getElementById('tablaHistorialBody');
            tbody.innerHTML = ''; // Limpiar tabla

            if(asientos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center p-3">No hay asientos registrados aún.</td></tr>';
                return;
            }

            asientos.forEach(asiento => {
                // Fila 1: Encabezado del Asiento (Fecha y Glosa)
                // Usamos un color de fondo suave para separar asientos
                let filasHTML = `
                    <tr class="table-secondary border-top border-dark">
                        <td class="fw-bold">${asiento.fecha}</td>
                        <td colspan="4" class="fst-italic text-muted">Asiento #${asiento.id}: ${asiento.glosa}</td>
                    </tr>
                `;

                // Filas N: Los detalles (Debe y Haber)
                asiento.movimientos.forEach(mov => {
                    // Indentación visual para el haber
                    const estiloNombre = mov.haber > 0 ? "padding-left: 40px;" : "fw-bold";
                    
                    filasHTML += `
                        <tr>
                            <td></td> <td style="${estiloNombre}">${mov.cuenta}</td>
                            <td class="text-center">${mov.folio || ''}</td>
                            <td class="text-end">${parseFloat(mov.debe) > 0 ? parseFloat(mov.debe).toFixed(2) : '-'}</td>
                            <td class="text-end">${parseFloat(mov.haber) > 0 ? parseFloat(mov.haber).toFixed(2) : '-'}</td>
                        </tr>
                    `;
                });
                
                tbody.innerHTML += filasHTML;
            });
        })
        .catch(err => console.error("Error cargando historial:", err));
    }

    // 3. Modifica tu evento 'btnGuardarAsiento' existente
    document.getElementById('btnGuardarAsiento').addEventListener('click', () => {
        // ... (Tu validación y creación del objeto datosParaEnviar) ...

        fetch('logica/LibroDiario.php', {
            method: 'POST',
            // ... headers y body ...
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                // Limpiar formulario...
                detallesAsiento = [];
                document.getElementById('glosaAsiento').value = '';
                renderizarTabla(); 

                cargarHistorial(); // <--- AGREGA ESTO AQUÍ para actualizar la tabla de abajo automáticamente
            }
        });
    });


        /*fin prueba  */

        // 3. Renderizar la Tabla (Dibujar HTML basado en el array)
        function renderizarTabla() {
            const tbody = document.getElementById('tablaDetalleBody');
            tbody.innerHTML = '';
            totalDebe = 0;
            totalHaber = 0;

            // Dibujar filas con datos
            detallesAsiento.forEach((item, index) => {
                totalDebe += item.debe;
                totalHaber += item.haber;

                // Identación visual para el Haber (estándar contable)
                const nombreClase = item.haber > 0 ? 'ps-5' : 'text-start'; 

                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td class="${nombreClase} text-start">${item.nombre_cuenta}</td>
                        <td>${item.folio}</td>
                        <td class="text-end">${item.debe > 0 ? item.debe.toFixed(2) : '-'}</td>
                        <td class="text-end">${item.haber > 0 ? item.haber.toFixed(2) : '-'}</td>
                        <td><button class="btn btn-sm btn-outline-danger" onclick="eliminarLinea(${index})">x</button></td>
                    </tr>
                `;
            });

            // Rellenar con filas vacías para mantener la estética de "hoja"
            const filasFaltantes = 5 - detallesAsiento.length;
            for (let i = 0; i < filasFaltantes; i++) {
                tbody.innerHTML += `<tr style="height: 40px;"><td></td><td></td><td></td><td></td><td></td><td></td></tr>`;
            }

            // Actualizar Totales
            document.getElementById('totalDebeDisplay').innerText = totalDebe.toFixed(2);
            document.getElementById('totalHaberDisplay').innerText = totalHaber.toFixed(2);

            // Validar Cuadre
            const mensajeDiv = document.getElementById('mensajeCuadre');
            if (detallesAsiento.length > 0) {
                if (Math.abs(totalDebe - totalHaber) < 0.01) {
                    mensajeDiv.className = "mt-2 fw-bold text-success small";
                    mensajeDiv.innerText = "✔ El asiento está cuadrado.";
                } else {
                    mensajeDiv.className = "mt-2 fw-bold text-danger small";
                    mensajeDiv.innerText = "⚠ El asiento NO cuadra (Diferencia: " + (totalDebe - totalHaber).toFixed(2) + ")";
                }
            } else {
                mensajeDiv.innerText = "";
            }
        }

        // 4. Eliminar una línea específica
        window.eliminarLinea = function(index) {
            detallesAsiento.splice(index, 1);
            renderizarTabla();
        }

        // 5. Limpiar todo
        window.limpiarAsiento = function() {
            if(confirm('¿Borrar todo el asiento actual?')) {
                detallesAsiento = [];
                document.getElementById('fechaAsiento').value = '';
                document.getElementById('glosaAsiento').value = '';
                renderizarTabla();
            }
        }

        // 6. GUARDAR EN BASE DE DATOS
        document.getElementById('btnGuardarAsiento').addEventListener('click', () => {
            const fecha = document.getElementById('fechaAsiento').value;
            const glosa = document.getElementById('glosaAsiento').value;

            // Validaciones finales
            if (!fecha) { alert("Falta la fecha."); return; }
            if (detallesAsiento.length < 2) { alert("Un asiento debe tener al menos 2 líneas."); return; }
            if (Math.abs(totalDebe - totalHaber) > 0.01) { 
                if(!confirm("El asiento no cuadra. ¿Desea guardarlo de todas formas?")) return;
            }

            const datosParaEnviar = {
                fecha: fecha,
                glosa: glosa,
                detalles: detallesAsiento
            };

            // Enviar a PHP
            fetch('logica/LibroDiario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datosParaEnviar)
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    // Reiniciar formulario tras éxito
                    detallesAsiento = [];
                    document.getElementById('glosaAsiento').value = '';
                    renderizarTabla();
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error de conexión con el servidor.");
            });
        });
    </script>
</body>
</html>