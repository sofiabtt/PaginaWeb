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
    <link rel="stylesheet" href="css/footer.css">


</head>

<body>

<?php include "includes/navbar.php"; ?>

<main class="contenido-resultados">


    <div class="container-fluid px-4 pt-5">

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
                                                    href="vueloElegido.php?codVuelo=<?php echo $vuelo['codVuelo']; ?>"
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


                    <div class="mb-4">


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
                                                        href="vueloElegido.php?codVuelo=<?php echo $vuelo['codVuelo']; ?>"
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


<script
    src="js/bootstrap.bundle.min.js"
></script>


</body>

</html>