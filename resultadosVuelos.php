<?php

session_start();

include "php/conexionBD.php";

include "php/filtrosVuelos.php";

?>

<!DOCTYPE html>

<html lang="es">


<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Vuelos</title>

    <link rel="icon" type="image/png" href="/PaginaWeb/imagenes/logo.png">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">

    <link rel="stylesheet" href="css/estiloshome.css?v=2">
    <link rel="stylesheet" href="css/estilosResultados.css?v=5">
    <link rel="stylesheet" href="css/estilos-usuario.css?v=2">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/navbar.css">


    <style>
        .progreso-reserva {
            background: #f8f6f3;
            border: 1px solid rgba(122, 74, 46, 0.12);
            border-radius: 22px;
            padding: 24px 28px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .progreso-titulo {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 10px;
            margin-bottom: 22px;
        }

        .progreso-titulo .meta-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #7a4a2e;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 0;
        }

        .progreso-titulo .meta-valor {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2f2f2f;
            margin: 0;
        }

        .timeline-pasos {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
        }

        .timeline-paso {
            flex: 1 1 220px;
            min-width: 200px;
            position: relative;
            padding-top: 26px;
        }

        .timeline-paso::before {
            content: "";
            position: absolute;
            top: 11px;
            left: 0;
            right: 0;
            height: 3px;
            background: #d9d5d0;
            z-index: 1;
        }

        .timeline-paso:last-child::before {
            right: 50%;
        }

        .timeline-paso:first-child::before {
            left: 50%;
        }

        .timeline-punto {
            position: absolute;
            top: 0;
            left: 50%;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            border: 3px solid #c7b8ab;
            z-index: 2;
        }

        .timeline-paso.completado::before,
        .timeline-paso.activo::before {
            background: #9c673e;
        }

        .timeline-paso.completado .timeline-punto,
        .timeline-paso.activo .timeline-punto {
            border-color: #9c673e;
            background: #9c673e;
        }

        .timeline-paso.activo .timeline-punto {
            box-shadow: 0 0 0 6px rgba(156, 103, 62, 0.15);
        }

        .timeline-contenido {
            text-align: center;
        }

        .timeline-etiqueta {
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #7a4a2e;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
        }

        .timeline-nombre {
            font-size: 1rem;
            font-weight: 700;
            color: #2f2f2f;
            margin-bottom: 4px;
        }

        .timeline-detalle {
            font-size: 0.92rem;
            color: #6f6f6f;
            line-height: 1.4;
            margin: 0 auto;
            max-width: 220px;
        }

        @media (max-width: 767.98px) {
            .progreso-reserva {
                padding: 20px;
            }

            .timeline-pasos {
                flex-direction: column;
                gap: 16px;
            }

            .timeline-paso {
                padding-top: 0;
                padding-left: 42px;
                min-width: auto;
            }

            .timeline-paso::before {
                top: 0;
                bottom: -16px;
                left: 11px;
                right: auto;
                width: 3px;
                height: auto;
            }

            .timeline-paso:first-child::before,
            .timeline-paso:last-child::before {
                left: 11px;
                right: auto;
            }

            .timeline-punto {
                top: 0;
                left: 0;
                transform: none;
            }

            .timeline-contenido {
                text-align: left;
            }

            .timeline-detalle {
                max-width: none;
                margin: 0;
            }
        }
    </style>

</head>

<body>

<?php include ("includes/navbar.php"); ?>

<main class="contenido-resultados">


    <div class="container-fluid px-4 pt-5">

        <?php if ($tipoViaje === "soloIda"): ?>
            <div class="progreso-reserva mb-5">
                <div class="progreso-titulo">

                    <h2 class="meta-valor">Obtener vuelo</h2>
                </div>

                <div class="timeline-pasos">
                    <div class="timeline-paso activo" id="pasoSoloIda1">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 1</span>
                            <div class="timeline-nombre">Elegir vuelo de ida</div>
                            <p class="timeline-detalle">
                                Seleccioná el vuelo que mejor se adapte a tu viaje.
                            </p>
                        </div>
                    </div>

                    <div class="timeline-paso" id="pasoSoloIda2">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 2</span>
                            <div class="timeline-nombre">Completar la reserva</div>
                            <p class="timeline-detalle">
                                Ingresá los datos de los pasajeros y confirmá la reserva.
                            </p>
                        </div>
                    </div>

                    <div class="timeline-paso" id="pasoSoloIda3">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 3</span>
                            <div class="timeline-nombre">Finalizar en Mis Reservas</div>
                            <p class="timeline-detalle">
                                Terminá el proceso desde la sección Mis Reservas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>


        <!--ENCABEZADO -->

        <div class="encabezado-vuelos mb-5">

            <h1 class="titulo-resultados">

                <?php if ($tipoViaje === "idaVuelta"): ?>

                    Vuelos de ida y vuelta

                <?php else: ?>

                    Vuelos de ida

                <?php endif; ?>

            </h1>


            <div class="resumen-vuelo">

                <div class="resumen-aeropuerto">

                    <span class="codigo-aeropuerto">

                        <?php

                        echo htmlspecialchars(
                            explode(" - ", $origen)[1] ?? $origen
                        );

                        ?>

                    </span>

                    <span class="ciudad-aeropuerto">

                        <?php

                        echo htmlspecialchars(
                            explode(" - ", $origen)[0] ?? $origen
                        );

                        ?>

                    </span>


                    <?php if ($fechaIda !== ""): ?>

                        <span class="fecha-aeropuerto">

                            <i class="bi bi-calendar3"></i>

                            <?php

                            echo htmlspecialchars(
                                $fechaIda
                            );

                            ?>

                        </span>

                    <?php endif; ?>

                </div>


                <div class="flecha-ruta">

                    <i class="bi bi-arrow-right"></i>

                </div>


                <div class="resumen-aeropuerto">

                    <span class="codigo-aeropuerto">

                        <?php

                        echo htmlspecialchars(
                            explode(" - ", $destino)[1] ?? $destino
                        );

                        ?>

                    </span>

                    <span class="ciudad-aeropuerto">

                        <?php

                        echo htmlspecialchars(
                            explode(" - ", $destino)[0] ?? $destino
                        );

                        ?>

                    </span>


                    <?php if (
                        $tipoViaje === "idaVuelta"
                        && $fechaVuelta !== ""
                    ): ?>

                        <span class="fecha-aeropuerto">

                            <i class="bi bi-calendar3"></i>

                            <?php

                            echo htmlspecialchars(
                                $fechaVuelta
                            );

                            ?>

                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </div>



        <div class="row g-4">


            <!-- FILTROS-->

            <aside class="col-lg-3">


                <div
                    class="card filtros-vuelos border-0 shadow-sm"
                >


                    <div class="card-body p-4">


                        <h2 class="h5 mb-4">


                            <i class="bi bi-funnel"></i>


                            Filtrar vuelos


                        </h2>



                        <form
                            action="resultadosVuelos.php"
                            method="GET"
                        >


                            <!-- TIPO DE VIAJE -->

                            <input
                                type="hidden"
                                name="tipoViaje"
                                value="<?php

                                echo htmlspecialchars(
                                    $tipoViaje
                                );

                                ?>"
                            >


                            <!-- ORIGEN -->

                            <input
                                type="hidden"
                                name="origen"
                                value="<?php

                                echo htmlspecialchars(
                                    $origen
                                );

                                ?>"
                            >


                            <!-- DESTINO -->

                            <input
                                type="hidden"
                                name="destino"
                                value="<?php

                                echo htmlspecialchars(
                                    $destino
                                );

                                ?>"
                            >


                            <!-- FECHA DE IDA -->

                            <input
                                type="hidden"
                                name="fechaIda"
                                value="<?php

                                echo htmlspecialchars(
                                    $fechaIda
                                );

                                ?>"
                            >


                            <!-- FECHA DE VUELTA -->

                            <input
                                type="hidden"
                                name="fechaVuelta"
                                value="<?php

                                echo htmlspecialchars(
                                    $fechaVuelta
                                );

                                ?>"
                            >


                            <!-- AEROLÍNEA -->

                            <div class="mb-4">


                                <label
                                    for="aerolinea"
                                    class="form-label fw-bold"
                                >

                                    Aerolínea

                                </label>


                                <select
                                    class="form-select"
                                    id="aerolinea"
                                    name="aerolinea"
                                >


                                    <option value="">

                                        Todas

                                    </option>


                                    <?php foreach (
                                        $aerolineas
                                        as $aerolineaBD
                                    ): ?>


                                        <option
                                            value="<?php

                                            echo $aerolineaBD[
                                                "codAerolinea"
                                            ];

                                            ?>"

                                            <?php

                                            if (
                                                $aerolinea ==
                                                $aerolineaBD[
                                                    "codAerolinea"
                                                ]
                                            ) {

                                                echo "selected";

                                            }

                                            ?>
                                        >


                                            <?php

                                            echo htmlspecialchars(
                                                $aerolineaBD[
                                                    "nombreAerolinea"
                                                ]
                                            );

                                            ?>


                                        </option>


                                    <?php endforeach; ?>


                                </select>


                            </div>



                            <!-- PRECIO -->

                            <div class="mb-4">


                                <label
                                    class="form-label fw-bold"
                                >

                                    Precio

                                </label>


                                <div class="row g-2">


                                    <div class="col-6">


                                        <input
                                            type="number"
                                            class="form-control"
                                            name="precioMin"
                                            placeholder="Desde"
                                            min="0"
                                            value="<?php

                                            echo htmlspecialchars(
                                                $precioMin
                                            );

                                            ?>"
                                        >


                                    </div>


                                    <div class="col-6">


                                        <input
                                            type="number"
                                            class="form-control"
                                            name="precioMax"
                                            placeholder="Hasta"
                                            min="0"
                                            value="<?php

                                            echo htmlspecialchars(
                                                $precioMax
                                            );

                                            ?>"
                                        >


                                    </div>


                                </div>


                            </div>



                            <!-- HORARIO -->

                            <div class="mb-4">


                                <label
                                    for="horario"
                                    class="form-label fw-bold"
                                >

                                    Horario

                                </label>


                                <select
                                    class="form-select"
                                    id="horario"
                                    name="horario"
                                >


                                    <option value="">

                                        Todos

                                    </option>


                                    <option
                                        value="manana"

                                        <?php

                                        if (
                                            $horario ===
                                            "manana"
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        Mañana

                                    </option>


                                    <option
                                        value="tarde"

                                        <?php

                                        if (
                                            $horario ===
                                            "tarde"
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        Tarde

                                    </option>


                                    <option
                                        value="noche"

                                        <?php

                                        if (
                                            $horario ===
                                            "noche"
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        Noche

                                    </option>


                                </select>


                            </div>



                            <!-- ORDEN -->

                            <div class="mb-4">


                                <label
                                    for="orden"
                                    class="form-label fw-bold"
                                >

                                    Ordenar por

                                </label>


                                <select
                                    class="form-select"
                                    id="orden"
                                    name="orden"
                                >


                                    <option
                                        value="hora"

                                        <?php

                                        if (
                                            $orden ===
                                            "hora"
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        Horario

                                    </option>


                                    <option
                                        value="precio_asc"

                                        <?php

                                        if (
                                            $orden ===
                                            "precio_asc"
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        Precio: menor a mayor

                                    </option>


                                    <option
                                        value="precio_desc"

                                        <?php

                                        if (
                                            $orden ===
                                            "precio_desc"
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        Precio: mayor a menor

                                    </option>


                                </select>


                            </div>



                            <button
                                type="submit"
                                class="btn btn-nuvia w-100"
                            >

                                Aplicar filtros

                            </button>


                        </form>


                    </div>


                </div>


            </aside>



            <!-- RESULTADOS-->

            <section class="col-lg-9">


                <!-- IDA-->

                <div class="mb-4">


                    <h2 class="h4 mb-1">


                        <i
                            class="bi bi-arrow-right"
                        ></i>


                        Vuelo de ida


                    </h2>


                </div>



                <?php if (
                    $cantidadIda > 0
                ): ?>


                    <div
                        class="d-flex flex-column gap-3 mb-5"
                    >


                        <?php foreach (
                            $vuelosIda
                            as $vuelo
                        ): ?>


                            <div
                                class="card vuelo-card border-0 shadow-sm"
                            >


                                <div
                                    class="card-body p-4"
                                >


                                    <div
                                        class="row align-items-center g-4"
                                    >


                                        <div
                                            class="col-lg-9"
                                        >


                                            <div
                                                class="d-flex align-items-center gap-2 mb-3"
                                            >


                                                <i
                                                    class="bi bi-airplane aerolinea-icono"
                                                ></i>


                                                <span
                                                    class="aerolinea"
                                                >


                                                    <?php

                                                    echo htmlspecialchars(
                                                        $vuelo[
                                                            "nombreAerolinea"
                                                        ]
                                                    );

                                                    ?>


                                                </span>


                                            </div>



                                            <div
                                                class="row align-items-center"
                                            >


                                                <div
                                                    class="col-md-5"
                                                >


                                                    <strong
                                                        class="ruta-ciudad"
                                                    >


                                                        <?php

                                                        echo htmlspecialchars(
                                                            $vuelo[
                                                                "origenVuelo"
                                                            ]
                                                        );

                                                        ?>


                                                    </strong>


                                                    <span
                                                        class="ruta-label"
                                                    >

                                                        Salida

                                                    </span>


                                                </div>



                                                <div
                                                    class="col-md-2 text-center"
                                                >


                                                    <div
                                                        class="linea-vuelo"
                                                    >


                                                        <i
                                                            class="bi bi-airplane-fill"
                                                        ></i>


                                                    </div>


                                                </div>



                                                <div
                                                    class="col-md-5"
                                                >


                                                    <strong
                                                        class="ruta-ciudad"
                                                    >


                                                        <?php

                                                        echo htmlspecialchars(
                                                            $vuelo[
                                                                "destinoVuelo"
                                                            ]
                                                        );

                                                        ?>


                                                    </strong>


                                                    <span
                                                        class="ruta-label"
                                                    >

                                                        Llegada

                                                    </span>


                                                </div>


                                            </div>



                                            <div
                                                class="d-flex flex-wrap gap-4 mt-4"
                                            >


                                                <div
                                                    class="dato-vuelo"
                                                >


                                                    <i
                                                        class="bi bi-calendar3"
                                                    ></i>


                                                    <span>


                                                        <?php

                                                        echo htmlspecialchars(
                                                            $vuelo[
                                                                "fechaSalidaVuelo"
                                                            ]
                                                        );

                                                        ?>


                                                    </span>


                                                </div>



                                                <div
                                                    class="dato-vuelo"
                                                >


                                                    <i
                                                        class="bi bi-clock"
                                                    ></i>


                                                    <span>


                                                        <?php

                                                        echo htmlspecialchars(
                                                            $vuelo[
                                                                "horaSalidaVuelo"
                                                            ]
                                                        );

                                                        ?>


                                                    </span>


                                                </div>



                                                <div
                                                    class="dato-vuelo"
                                                >


                                                    <i
                                                        class="bi bi-person"
                                                    ></i>


                                                    <span>


                                                        <?php

                                                        echo htmlspecialchars(
                                                            $vuelo[
                                                                "asientosDisponibles"
                                                            ]
                                                        );

                                                        ?>


                                                        asientos


                                                    </span>


                                                </div>


                                            </div>


                                        </div>



                                        <div
                                            class="col-lg-3"
                                        >


                                            <div
                                                class="precio-vuelo"
                                            >


                                                <span
                                                    class="precio-label"
                                                >

                                                    Precio

                                                </span>


                                                <strong>


                                                    $


                                                    <?php

                                                    echo number_format(
                                                        $vuelo[
                                                            "precioVuelo"
                                                        ],
                                                        0,
                                                        ",",
                                                        "."
                                                    );

                                                    ?>


                                                </strong>


                                                <a
                                                    href="vueloElegido.php?codVuelo=<?php echo $vuelo['codVuelo']; ?>&tramo=ida&tipoViaje=<?php echo urlencode($tipoViaje); ?>&origen=<?php echo urlencode($origen); ?>&destino=<?php echo urlencode($destino); ?>&fechaIda=<?php echo urlencode($fechaIda); ?>&fechaVuelta=<?php echo urlencode($fechaVuelta); ?>"
                                                    class="btn btn-nuvia"
                                                >

                                                    Elegir vuelo

                                                </a>


                                            </div>


                                        </div>


                                    </div>


                                </div>


                            </div>


                        <?php endforeach; ?>


                    </div>


                <?php else: ?>


                    <div
                        class="card sin-vuelos border-0 shadow-sm mb-5"
                    >


                        <div
                            class="card-body py-5 text-center"
                        >


                            <i
                                class="bi bi-airplane icono-sin-vuelos"
                            ></i>


                            <h2 class="h5">

                                No encontramos vuelos de ida

                            </h2>


                            <p
                                class="text-muted mb-0"
                            >

                                No hay vuelos disponibles
                                para esta ruta y fecha.

                            </p>


                        </div>


                    </div>


                <?php endif; ?>



                <!-- VUELTA-->

                <?php if (
                    $tipoViaje === "idaVuelta"
                ): ?>


                    <hr class="my-5">


                    <div
                        id="vuelo-vuelta"
                        class="mb-4"
                    >


                        <h2 class="h4 mb-1">


                            <i
                                class="bi bi-arrow-left"
                            ></i>


                            Vuelo de vuelta


                        </h2>


                    </div>



                    <?php if (
                        $cantidadVuelta > 0
                    ): ?>


                        <div
                            class="d-flex flex-column gap-3"
                        >


                            <?php foreach (
                                $vuelosVuelta
                                as $vuelo
                            ): ?>


                                <div
                                    class="card vuelo-card border-0 shadow-sm"
                                >


                                    <div
                                        class="card-body p-4"
                                    >


                                        <div
                                            class="row align-items-center g-4"
                                        >


                                            <div
                                                class="col-lg-9"
                                            >


                                                <div
                                                    class="d-flex align-items-center gap-2 mb-3"
                                                >


                                                    <i
                                                        class="bi bi-airplane aerolinea-icono"
                                                    ></i>


                                                    <span
                                                        class="aerolinea"
                                                    >


                                                        <?php

                                                        echo htmlspecialchars(
                                                            $vuelo[
                                                                "nombreAerolinea"
                                                            ]
                                                        );

                                                        ?>


                                                    </span>


                                                </div>



                                                <div
                                                    class="row align-items-center"
                                                >


                                                    <div
                                                        class="col-md-5"
                                                    >


                                                        <strong
                                                            class="ruta-ciudad"
                                                        >


                                                            <?php

                                                            echo htmlspecialchars(
                                                                $vuelo[
                                                                    "origenVuelo"
                                                                ]
                                                            );

                                                            ?>


                                                        </strong>


                                                        <span
                                                            class="ruta-label"
                                                        >

                                                            Salida

                                                        </span>


                                                    </div>



                                                    <div
                                                        class="col-md-2 text-center"
                                                    >


                                                        <div
                                                            class="linea-vuelo"
                                                        >


                                                            <i
                                                                class="bi bi-airplane-fill"
                                                            ></i>


                                                        </div>


                                                    </div>



                                                    <div
                                                        class="col-md-5"
                                                    >


                                                        <strong
                                                            class="ruta-ciudad"
                                                        >


                                                            <?php

                                                            echo htmlspecialchars(
                                                                $vuelo[
                                                                    "destinoVuelo"
                                                                ]
                                                            );

                                                            ?>


                                                        </strong>


                                                        <span
                                                            class="ruta-label"
                                                        >

                                                            Llegada

                                                        </span>


                                                    </div>


                                                </div>



                                                <div
                                                    class="d-flex flex-wrap gap-4 mt-4"
                                                >


                                                    <div
                                                        class="dato-vuelo"
                                                    >


                                                        <i
                                                            class="bi bi-calendar3"
                                                        ></i>


                                                        <span>


                                                            <?php

                                                            echo htmlspecialchars(
                                                                $vuelo[
                                                                    "fechaSalidaVuelo"
                                                                ]
                                                            );

                                                            ?>


                                                        </span>


                                                    </div>



                                                    <div
                                                        class="dato-vuelo"
                                                    >


                                                        <i
                                                            class="bi bi-clock"
                                                        ></i>


                                                        <span>


                                                            <?php

                                                            echo htmlspecialchars(
                                                                $vuelo[
                                                                    "horaSalidaVuelo"
                                                                ]
                                                            );

                                                            ?>


                                                        </span>


                                                    </div>



                                                    <div
                                                        class="dato-vuelo"
                                                    >


                                                        <i
                                                            class="bi bi-person"
                                                        ></i>


                                                        <span>


                                                            <?php

                                                            echo htmlspecialchars(
                                                                $vuelo[
                                                                    "asientosDisponibles"
                                                                ]
                                                            );

                                                            ?>


                                                            asientos


                                                        </span>


                                                    </div>


                                                </div>


                                            </div>



                                            <div
                                                class="col-lg-3"
                                            >


                                                <div
                                                    class="precio-vuelo"
                                                >


                                                    <span
                                                        class="precio-label"
                                                    >

                                                        Precio

                                                    </span>


                                                    <strong>


                                                        $


                                                        <?php

                                                        echo number_format(
                                                            $vuelo[
                                                                "precioVuelo"
                                                            ],
                                                            0,
                                                            ",",
                                                            "."
                                                        );

                                                        ?>


                                                    </strong>


                                                    <a
                                                        href="vueloElegido.php?codVuelo=<?php echo $vuelo['codVuelo']; ?>&tramo=vuelta&tipoViaje=<?php echo urlencode($tipoViaje); ?>&origen=<?php echo urlencode($origen); ?>&destino=<?php echo urlencode($destino); ?>&fechaIda=<?php echo urlencode($fechaIda); ?>&fechaVuelta=<?php echo urlencode($fechaVuelta); ?>"
                                                        class="btn btn-nuvia"
                                                    >
                                                        Elegir vuelo
                                                    </a>


                                                </div>


                                            </div>


                                        </div>


                                    </div>


                                </div>


                            <?php endforeach; ?>


                        </div>


                    <?php else: ?>


                        <div
                            class="card sin-vuelos border-0 shadow-sm"
                        >


                            <div
                                class="card-body py-5 text-center"
                            >


                                <i
                                    class="bi bi-airplane icono-sin-vuelos"
                                ></i>


                                <h2 class="h5">

                                    No encontramos vuelos de vuelta

                                </h2>


                                <p
                                    class="text-muted mb-0"
                                >

                                    No hay vuelos disponibles
                                    para


                                    <?php

                                    echo htmlspecialchars(
                                        $destino
                                    );

                                    ?>


                                    →


                                    <?php

                                    echo htmlspecialchars(
                                        $origen
                                    );

                                    ?>


                                    en la fecha seleccionada.

                                </p>


                            </div>


                        </div>


                    <?php endif; ?>


                <?php endif; ?>


            </section>


        </div>


    </div>


</main>


<script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>