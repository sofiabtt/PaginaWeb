<?php if ($totalPaginas > 1) { ?>

    <nav class="paginacion-tablas" aria-label="Paginación">

        <p>
            Página <?= $pagina ?> de <?= $totalPaginas ?>
        </p>

        <div class="d-flex justify-content-center gap-2 flex-wrap">

            <?php if ($pagina > 1) { ?>

                <?php
                $parametros = $_GET;
                $parametros["pagina"] = $pagina - 1;
                ?>

                <a
                    href="?<?= htmlspecialchars(http_build_query($parametros), ENT_QUOTES, 'UTF-8') ?>"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-chevron-left"></i>
                    Anterior
                </a>

            <?php } ?>

            <?php if ($pagina < $totalPaginas) { ?>

                <?php
                $parametros = $_GET;
                $parametros["pagina"] = $pagina + 1;
                ?>

                <a
                    href="?<?= htmlspecialchars(http_build_query($parametros), ENT_QUOTES, 'UTF-8') ?>"
                    class="btn btn-outline-secondary"
                >
                    Siguiente
                    <i class="bi bi-chevron-right"></i>
                </a>

            <?php } ?>

        </div>

    </nav>

<?php } ?>