<?php

function configurarPaginacion($totalRegistros, $porPagina = 10)
{
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

    $pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);

    $pagina = ($pagina !== false && $pagina !== null && $pagina > 0)
        ? $pagina
        : 1;

    $pagina = min($pagina, $totalPaginas);

    $offset = ($pagina - 1) * $porPagina;

    return [
        "pagina" => $pagina,
        "porPagina" => $porPagina,
        "totalPaginas" => $totalPaginas,
        "offset" => $offset
    ];
}