<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

require_once __DIR__ . '/../../php/conexionBD.php';
require_once __DIR__ . '/../../php/consultasUsuarios.php';

/* FILTROS Y PAGINACIÓN*/

$filtroRol = isset($_GET['rol']) ? trim($_GET['rol']) : '';
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$porPagina = 10;
$paginaActual = isset($_GET['pag']) ? max(1, (int) $_GET['pag']) : 1;

/* CONSULTAS */

$totalUsuarios = cantidadUsuariosReporte($conexion, $filtroRol, $busqueda);

$totalPaginas = (int) ceil($totalUsuarios / $porPagina);

if ($totalPaginas > 0 && $paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

$inicio = ($paginaActual - 1) * $porPagina;

$resultado = obtenerUsuariosReporte(
    $conexion,
    $porPagina,
    $inicio,
    $filtroRol,
    $busqueda
);

// Todos los usuarios filtrados, sin paginación, para imprimir.
$resultadoImpresion = obtenerUsuariosReporte(
    $conexion,
    max(1, $totalUsuarios),
    0,
    $filtroRol,
    $busqueda
);

/* FUNCIÓN PARA ENLACES DE PAGINACIÓN*/

function enlacePaginaUsuarios($pagina, $rol, $buscar)
{
    return '?' . http_build_query([
        'pag' => $pagina,
        'rol' => $rol,
        'buscar' => $buscar
    ]);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Gestión de Reportes</title>
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>?v=20261108">
    <link rel="stylesheet" href="../../css/reportesResponsive.css">
</head>

<body class="nuvia-panel nuvia-navbar">
    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

    <main class="container reporte-contenido">

         <!-- ENCABEZADO DE IMPRESIÓN-->
        <div class="reporte-membrete">
            <h2>Nuvia - Sistema de Reservas de Vuelos</h2>

            <p>
                <strong>Reporte:</strong> Padrón General de Usuarios Registrados
            </p>

            <p>
                <strong>Fecha de emisión:</strong>
                <?= date('d/m/Y H:i') ?> hs
            </p>

            <p>
                <strong>Emitido por:</strong> Administrador
            </p>
        </div>

        <div class="reporte-encabezado">
            <div>
                <a href="gestionReportes.php" class="reporte-volver">
                    <i class="bi bi-arrow-left"></i>
                    Volver a Reportes
                </a>

                <h1>Reporte de Usuarios</h1>

                <p class="reporte-descripcion">
                    Total encontrados:
                    <strong><?= $totalUsuarios ?></strong>
                </p>
            </div>

            <button
                type="button"
                class="btn btn-outline-dark reporte-btn-imprimir"
                onclick="window.print()"
            >
                <i class="bi bi-printer"></i>
                Imprimir Reporte
            </button>
        </div>

        <!-- FILTROS-->
        <section class="reporte-filtros">
            <form method="GET" action="usuarios.php" class="row g-3 align-items-end">

                <!-- FILTRO POR ROL -->
                <div class="col-md-4">
                    <label for="rol" class="form-label">
                        Filtrar por Rol
                    </label>

                    <select
                        name="rol"
                        id="rol"
                        class="form-select"
                    >
                        <option value="">
                            Todos los usuarios
                        </option>

                        <option
                            value="usuario"
                            <?= ($filtroRol === 'usuario' || $filtroRol === 'cliente') ? 'selected' : '' ?>
                        >
                            Clientes / Pasajeros
                        </option>

                        <option
                            value="ceo"
                            <?= $filtroRol === 'ceo' ? 'selected' : '' ?>
                        >
                            CEOs de Aerolínea
                        </option>

                        <option
                            value="administrador"
                            <?= $filtroRol === 'administrador' ? 'selected' : '' ?>
                        >
                            Administradores
                        </option>
                    </select>
                </div>

                <!-- BÚSQUEDA -->
                <div class="col-md-5">
                    <label for="buscar" class="form-label">
                        Buscar por Nombre o Email
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control"
                            id="buscar"
                            name="buscar"
                            value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Nombre o correo electrónico"
                        >
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="col-md-3">
                    <div class="reporte-botones-filtro">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-filter"></i>
                            Filtrar
                        </button>

                        <?php if ($filtroRol !== '' || $busqueda !== ''): ?>

                            <a
                                href="usuarios.php"
                                class="btn btn-outline-secondary"
                            >
                                Limpiar
                            </a>

                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </section>

        <!-- TABLA DE USUARIOS -->
        <section class="reporte-tabla reporte-tabla-pantalla">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
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
                                    <!-- ID -->
                                    <td data-label="ID" class="fw-bold">
                                        #<?= (int) $u['codUsuario'] ?>
                                    </td>

                                    <!-- NOMBRE -->
                                    <td data-label="Nombre">
                                        <?= htmlspecialchars($u['nombreUsuario'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <!-- EMAIL -->
                                    <td data-label="Email">
                                        <?= htmlspecialchars($u['emailUsuario'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <!-- ROL -->
                                    <td data-label="Rol">

                                        <?php
                                            $rol = strtolower($u['tipoUsuario'] ?? '');

                                            if ($rol === 'administrador') {
                                                $claseRol = 'bg-danger';
                                                $textoRol = 'Administrador';
                                            } elseif ($rol === 'ceo') {
                                                $claseRol = 'bg-warning text-dark';
                                                $textoRol = 'CEO Aerolínea';
                                            } else {
                                                $claseRol = 'bg-primary';
                                                $textoRol = 'Cliente';
                                            }
                                        ?>

                                        <span class="badge <?= $claseRol ?>">
                                            <?= $textoRol ?>
                                        </span>

                                    </td>

                                    <!-- TELÉFONO -->
                                    <td data-label="Teléfono">
                                        <?= htmlspecialchars($u['telefonoUsuario'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <!-- ESTADO -->
                                    <td data-label="Estado">

                                        <?php if (!empty($u['verificado']) && $u['verificado'] == 1): ?>
                                            <span class="badge bg-success">
                                                Verificado
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                Pendiente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td
                                    colspan="6"
                                    class="reporte-sin-resultados"
                                >
                                    No se encontraron usuarios con los criterios seleccionados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN-->
            <?php if ($totalPaginas > 1): ?>

                <nav
                    class="reporte-paginacion"
                    aria-label="Navegación de páginas"
                >
                    <ul class="pagination">
                        <!-- ANTERIOR -->
                        <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                            <a
                                class="page-link"
                                href="<?= htmlspecialchars(enlacePaginaUsuarios(max(1, $paginaActual - 1), $filtroRol, $busqueda), ENT_QUOTES, 'UTF-8') ?>"
                            >
                                Anterior
                            </a>
                        </li>

                        <!-- NÚMEROS DE PÁGINA -->
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>">
                                <a
                                    class="page-link"
                                    href="<?= htmlspecialchars(enlacePaginaUsuarios($i, $filtroRol, $busqueda), ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- SIGUIENTE -->
                        <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                            <a
                                class="page-link"
                                href="<?= htmlspecialchars(enlacePaginaUsuarios(min($totalPaginas, $paginaActual + 1), $filtroRol, $busqueda), ENT_QUOTES, 'UTF-8') ?>"
                            >
                                Siguiente
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </section>

        <!-- TABLA COMPLETA PARA IMPRESIÓN -->
        <section class="reporte-tabla reporte-tabla-impresion">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($resultadoImpresion && mysqli_num_rows($resultadoImpresion) > 0): ?>
                        <?php while ($u = mysqli_fetch_assoc($resultadoImpresion)): ?>
                            <tr>
                                <td><?= (int) $u['codUsuario'] ?></td>

                                <td><?= htmlspecialchars($u['nombreUsuario'], ENT_QUOTES, 'UTF-8') ?></td>

                                <td><?= htmlspecialchars($u['emailUsuario'], ENT_QUOTES, 'UTF-8') ?></td>

                                <td><?= htmlspecialchars($u['tipoUsuario'], ENT_QUOTES, 'UTF-8') ?></td>

                                <td><?= htmlspecialchars($u['telefonoUsuario'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>

                                <td>
                                    <?= !empty($u['verificado']) ? 'Verificado' : 'Pendiente' ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No se encontraron usuarios.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>