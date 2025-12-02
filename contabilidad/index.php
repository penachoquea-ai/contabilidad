<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Contable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            /* Imagen de fondo de una empresa */
            background-image: url('imagenes/fondo-empresa.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding-top: 70px; /* Espacio para la barra de navegación fija */
            display: flex;
            align-items: center; /* Centrado vertical */
            justify-content: center; /* Centrado horizontal */
            min-height: 100vh;
        }
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            transition: transform .2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Contenido Principal -->
    <!-- Barra de Navegación Superior -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-calculator-fill"></i>
                Contabilidad
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="Plan_Cuenta.php"><i class="bi bi-journal-bookmark-fill"></i> Plan de Cuentas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Libro_Diario.php"><i class="bi bi-book-fill"></i> Libro Diario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Libro_mayor.php"><i class="bi bi-journal-album"></i> Libro Mayor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Balance_comprobación.php"><i class="bi bi-bar-chart-line-fill"></i> Balance</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <span class="navbar-text me-3">
                            Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? $_SESSION['usuario']); ?></strong>
                        </span>
                        <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Iniciar Sesión</a>
                        <!-- Opcional: Botón de registro que crearemos más adelante -->
                        <!-- <a href="register.php" class="btn btn-primary">Registrarse</a> -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido del Dashboard -->
    <div class="container">
        <div class="p-5 rounded-3" style="background-color: rgba(0, 0, 0, 0.5);">
            <h2 class="mb-4 text-white">Panel de Estadísticas</h2>
            <div class="row justify-content-center">
                <!-- Tarjeta de Estadística 1 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card stat-card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Activos</h5>
                            <p class="card-text fs-2">$ 15,250.00</p>
                            <small>Actualizado hoy</small>
                        </div>
                    </div>
                </div>
                <!-- Puedes agregar más tarjetas aquí -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
