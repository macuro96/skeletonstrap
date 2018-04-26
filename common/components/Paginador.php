<?php

namespace common\components;

class Paginador
{
    public static function crear($iPaginaActual, $iMaxElementosPorPagina, $iTotalElementos, $anchorEnlace = '')
    {
        $iMaxPaginas      = ceil($iTotalElementos / $iMaxElementosPorPagina);
        $iPaginaPrevia    = ($iPaginaActual == 1 ? 1 : $iPaginaActual - 1);
        $iPaginaSiguiente = ($iPaginaActual == $iMaxPaginas ? $iMaxPaginas : $iPaginaActual + 1);
        ?>
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <li <?= ($iPaginaActual <= 1 ? 'class="disabled"' : '') ?> >
                <a href="#<?= $anchorEnlace ?>" data-pagina="<?= $iPaginaPrevia ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($p = 1; $p <= $iMaxPaginas; $p++) : ?>
                <li <?= ($iPaginaActual == $p ? 'class="active"' : '') ?> data-pagina="<?= $p ?>"><a href="#"><?= $p ?></a></li>
            <?php endfor;
            ?>
            <li <?= ($iPaginaActual >= $iMaxPaginas ? 'class="disabled"' : '') ?>>
              <a href="#<?= $anchorEnlace ?>" data-pagina="<?= $iPaginaSiguiente ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
        <?php
    }
}
