<?php
session_start();

// Control de acceso: solo Administrador
if (!isset($_SESSION['codUsuario']) || strtolower($_SESSION['tipoUsuario'] ?? '') !== 'administrador') {
    header("Location: ../../inicioSesion.php");
    exit();
}

include_once __DIR__ . "/../../php/conexionBD.php";
include_once __DIR__ . "/../../php/consultasUsuarios.php";

// Filtros y Paginación
$filtroRol = isset($_GET['rol']) ? trim($_GET['rol']) : '';
$busqueda  = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$porPagina = 10;
$paginaActual = isset($_GET['pag']) ? max(1, intval($_GET['pag'])) : 1;
$inicio = ($paginaActual - 1) * $porPagina;

// Consultas
$totalUsuarios = cantidadUsuariosReporte($conexion, $filtroRol, $busqueda);
$totalPaginas  = ceil($totalUsuarios / $porPagina);
$resultado     = obtenerUsuariosReporte($conexion, $porPagina, $inicio, $filtroRol, $busqueda);

$rutaBase = file_exists(__DIR__ . '/../../css/bootstrap.min.css') ? '../../' : '../';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia Admin - Reporte de Usuarios</title>

    <!-- Estilos de pantalla -->
    <link rel="stylesheet" href="<?php echo $rutaBase; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $rutaBase; ?>css/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo $rutaBase; ?>css/navbar.css">
    <link rel="stylesheet" href="<?php echo $rutaBase; ?>css/estilos-admin.css">

    <!-- Respaldo CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .navbar-brand img {
            max-height: 40px !important;
            width: auto !important;
        }

        /* ==========================================
           REGLAS EXCLUSIVAS PARA IMPRESIÓN (@media print)
           ========================================== */
        @media print {
            /* 1. Ocultar elementos innecesarios */
            nav, 
            .navbar, 
            footer, 
            .btn, 
            form, 
            .card-footer, 
            .pagination, 
            .d-print-none,
            a[href="../admin.php"] {
                display: none !important;
            }

            /* 2. Limpiar fondo y colores para ahorrar tinta */
            body, .bg-light {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 11pt;
            }

            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            /* 3. Tabla de ancho completo y bordes nítidos */
            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            .table-dark {
                background-color: #f1f1f1 !important;
                color: #000000 !important;
                border-bottom: 2px solid #000000 !important;
            }

            th, td {
                border: 1px solid #cccccc !important;
                padding: 6px 8px !important;
                color: #000000 !important;
            }

            /* 4. Badges legibles en impresión */
            .badge {
                border: 1px solid #333333 !important;
                color: #000000 !important;
                background-color: transparent !important;
                font-weight: 600 !important;
            }
        }
    </style>
</head>
<body class="bg-light">

    <?php 
        $pathNavbar = file_exists(__DIR__ . '/../includes/navbarAdmin.php') 
            ? __DIR__ . '/../includes/navbarAdmin.php' 
            : __DIR__ . '/includes/navbarAdmin.php';
        include $pathNavbar; 
    ?>

    <main class="container py-4">

        <!-- Membrete exclusivo para la hoja impresa (en pantalla está oculto) -->
        <div class="d-none d-print-block mb-3 border-bottom pb-2">
            <h2 class="h4 fw-bold mb-1">Nuvia - Sistema de Reservas de Vuelos</h2>
            <p class="small text-muted mb-0">
                <strong>Reporte:</strong> Padrón General de Usuarios Registrados &nbsp;|&nbsp; 
                <strong>Fecha de emisión:</strong> <?php echo date('d/m/Y H:i'); ?> hs &nbsp;|&nbsp;
                <strong>Emitido por:</strong> Administrador
            </p>
        </div>

        <!-- Encabezado en pantalla -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="../admin.php" class="text-decoration-none text-muted mb-2 d-inline-block d-print-none">
                    <i class="bi bi-arrow-left"></i> Volver al Panel
                </a>
                <h1 class="h3 mb-0 text-dark fw-bold">Reporte de Usuarios</h1>
                <p class="text-muted small mb-0">Total encontrados: <strong><?php echo $totalUsuarios; ?></strong></p>
            </div>
            
            <!-- Botón Imprimir Reporte -->
            <div class="d-print-none">
                <button type="button" onclick="window.print();" class="btn btn-outline-dark shadow-sm">
                    <i class="bi bi-printer me-1"></i> Imprimir Reporte
                </button>
            </div>
        </div>

        <!-- Filtros y Búsqueda (se ocultan automáticamente al imprimir) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
            <div class="card-body p-3">
                <form method="GET" action="usuarios.php" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="rol" class="form-label small text-muted fw-semibold">Filtrar por Rol</label>
                        <select class="form-select" id="rol" name="rol">
                            <option value="">Todos los usuarios</option>
                            <option value="usuario" <?php if ($filtroRol === 'usuario' || $filtroRol === 'cliente') echo 'selected'; ?>>Clientes / Pasajeros</option>
                            <option value="ceo" <?php if ($filtroRol === 'ceo') echo 'selected'; ?>>CEOs de Aerolínea</option>
                            <option value="administrador" <?php if ($filtroRol === 'administrador') echo 'selected'; ?>>Administradores</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label for="buscar" class="form-label small text-muted fw-semibold">Buscar por Nombre o Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="buscar" name="buscar" 
                                   value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Ej: Perez, sofia@mail.com...">
                        </div>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                        <?php if ($filtroRol !== '' || $busqueda !== ''): ?>
                            <a href="usuarios.php" class="btn btn-outline-secondary">Limpiar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla del Reporte -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="ps-3">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                            <?php while ($u = mysqli_fetch_assoc($resultado)): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">#<?php echo $u['codUsuario']; ?></td>
                                    <td class="fw-semibold text-dark">
                                        <?php echo htmlspecialchars($u['nombreUsuario']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($u['emailUsuario']); ?></td>
                                    <td>
                                        <?php 
                                            $rol = strtolower($u['tipoUsuario'] ?? '');
                                            if ($rol === 'administrador') {
                                                echo '<span class="badge bg-danger">Administrador</span>';
                                            } elseif ($rol === 'ceo') {
                                                echo '<span class="badge bg-warning text-dark">CEO Aerolínea</span>';
                                            } else {
                                                echo '<span class="badge bg-primary">Cliente</span>';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($u['telefonoUsuario'] ?? '-'); ?></td>
                                    <td>
                                        <?php if (!empty($u['verificado']) && $u['verificado'] == 1): ?>
                                            <span class="badge bg-success">Verificado</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No se encontraron usuarios con los criterios seleccionados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación (se oculta al imprimir) -->
            <?php if ($totalPaginas > 1): ?>
                <div class="card-footer bg-white border-0 py-3 rounded-bottom-4 d-print-none">
                    <nav aria-label="Navegación de páginas">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?php if ($paginaActual <= 1) echo 'disabled'; ?>">
                                <a class="page-link" href="?pag=<?php echo $paginaActual - 1; ?>&rol=<?php echo urlencode($filtroRol); ?>&buscar=<?php echo urlencode($busqueda); ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?php if ($i == $paginaActual) echo 'active'; ?>">
                                    <a class="page-link" href="?pag=<?php echo $i; ?>&rol=<?php echo urlencode($filtroRol); ?>&buscar=<?php echo urlencode($busqueda); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php if ($paginaActual >= $totalPaginas) echo 'disabled'; ?>">
                                <a class="page-link" href="?pag=<?php echo $paginaActual + 1; ?>&rol=<?php echo urlencode($filtroRol); ?>&buscar=<?php echo urlencode($busqueda); ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php 
        $pathFooter = file_exists(__DIR__ . '/../includes/footerAdmin.php') 
            ? __DIR__ . '/../includes/footerAdmin.php' 
            : __DIR__ . '/includes/footerAdmin.php';
        include $pathFooter; 
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>