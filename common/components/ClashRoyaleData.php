<?php

namespace common\components;

class ClashRoyaleData
{
    private static $_debug = false;

    private $_url;
    private $_dominioUrl;
    private $_contenidoWebJugador;
    private $_contenidoWebClan;
    private $_contenidoWebCartasJugador;

    private $_patrones;

    const ETIQUETAS_FILTRADO = '<span><div><a>';

    const RUTA_JUGADOR        = 'profile';
    const RUTA_CLAN           = 'clan';
    const RUTA_CARTAS_JUGADOR = 'cards';

    public function __construct()
    {
        $this->_dominioUrl = getenv('URL_DATA_CR');
        $this->_url = 'https://' . $this->_dominioUrl . '/es/';

        $this->_patrones = [
            'jugador' => [
                'clan'                 => '/<a href=\'https:\/\/' . $this->_dominioUrl . '\/es\/clan\/(.*?)\' class="ui__link ui__mediumText ui__whiteText profileHeader__userClan">/su',
                'nombre'               => '/<span class="profileHeader__nameCaption">(.*?)<\/span>/su',
                'nivel'                => '/<span class="profileHeader__userLevel">(.*?)<\/span>/su',
                'trofeos'              => '/<div class="statistics__metricCaption ui__mediumText">Trofeos<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'maxTrofeos'           => '/<div class="statistics__metricCaption ui__mediumText">Máximo de trofeos<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'totalPartidas'        => '/<div class="statistics__metricCaption ui__mediumText">Partidas jugadas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'victorias'            => '/<div class="statistics__metricCaption ui__mediumText">Victorias<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'derrotas'             => '/<div class="statistics__metricCaption ui__mediumText">Derrotas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'victoriasTresCoronas' => '/<div class="statistics__metricCaption ui__mediumText">Victorias de tres coronas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'totalDonaciones'      => '/<div class="statistics__metricCaption ui__mediumText">Donaciones totales<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'victoriasDesafio'     => '/<div class="statistics__metricCaption ui__mediumText">Victorias máx.<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'cartasGanadasDesafio' => '/<div class="statistics__metricCaption ui__mediumText">Cartas ganadas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'arena'                => '/<div class="statistics__metricCaption ui__mediumText">Liga<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su'
            ],
            'cartasJugador' => [
                'cartasDescubiertas' => '/<a href="https:\/\/' . $this->_dominioUrl . '\/es\/card\/(.*?)">/su'
            ],
            'clan' => [
                'jugador' => [
                    'donaciones' => '/<a class="ui__blueLink" href=\'https:\/\/' . $this->_dominioUrl . '\/es\/profile\/_TAG_\'>.*?<div class="clan__donation">(.*?)<\/div>/su',
                ],
                'trofeos' => '',
                'donacionesSemana' => ''
            ],
        ];
    }

    ////// STATIC /////////

    public static function setDebug($bDebug)
    {
        static::$_debug = $bDebug;
    }

    ///////////////////////

    ////// GETTERS /////////

    public function getContenidoWebJugador()
    {
        return $this->_contenidoWebJugador;
    }

    public function getContenidoWebClan()
    {
        return $this->_contenidoWebClan;
    }

    public function getContenidoWebCartasJugador()
    {
        return $this->_contenidoWebCartasJugador;
    }

    public function getPatron($clave, $datoTemp, $parametroReplace = null)
    {
        $patron = $this->_patrones[$clave];

        $aDatos       = explode('.', $datoTemp);
        $nDimensiones = count($aDatos);

        $patron = (object) $patron;

        for ($i = 0; $i < count($aDatos); $i++) {
            $nombrePropiedad = $aDatos[$i];
            $patron = (object) $patron->{$nombrePropiedad};
        }

        $patron = $patron->scalar;

        if ($patron === null) {
            throw new \Exception('Ha ocurrido un error en la búsqueda');
        }

        if ($parametroReplace != null) {
             $patron = str_replace('_TAG_', $parametroReplace, $patron);
        }

        return $patron;
    }

    ////////////////////////

    ///// SETTERS /////////

    private function setContenidoWebJugador($tag)
    {
        if (!$this->_contenidoWebJugador) {
            $subRutaWeb = static::RUTA_JUGADOR . '/' . $tag;

            $this->_contenidoWebJugador = $this->filtrarContenidoWeb($subRutaWeb);
        }
    }

    private function setContenidoWebClan($tag)
    {
        if (!$this->_contenidoWebClan) {
            $subRutaWeb = static::RUTA_CLAN . '/' . $tag;

            $this->_contenidoWebClan = $this->filtrarContenidoWeb($subRutaWeb);
        }
    }

    private function setContenidoWebCartasJugador($tagJugador)
    {
        if (!$this->_contenidoWebCartasJugador) {
            $subRutaWeb = static::RUTA_JUGADOR . '/' . $tagJugador . '/' .  static::RUTA_CARTAS_JUGADOR;

            $this->_contenidoWebCartasJugador = $this->filtrarContenidoWeb($subRutaWeb);
        }
    }

    ////////////////////////

    ///// METODOS /////////

    public function jugador($tagJugador)
    {
        $this->setContenidoWebJugador($tagJugador);
        $this->setContenidoWebCartasJugador($tagJugador);

        $clavesPatronesJugador = array_keys($this->_patrones['jugador']);

        for ($i = 0; $i < count($clavesPatronesJugador); $i++) {
            $aPatrones[$clavesPatronesJugador[$i]] = $this->getPatron('jugador', $clavesPatronesJugador[$i]);
        }

        $aPatrones['cartasDescubiertas'] = $this->getPatron('cartasJugador', 'cartasDescubiertas');
        $aPatrones['donacionesClan'] = $this->getPatron('clan', 'jugador.donaciones', $tagJugador);

        $aCoincidencias = [];

        for ($i = 0; $i < count($clavesPatronesJugador); $i++) {
            $aCoincidencias[$clavesPatronesJugador[$i]] = $this->coincidenciasPatron([
                'patron' => $aPatrones[$clavesPatronesJugador[$i]],
                'clave'  => 'jugador'
            ]);
        }

        $aCoincidencias['cartasDescubiertas'] = count($this->coincidenciasPatron([
            'patron' => $aPatrones['cartasDescubiertas'],
            'clave'  => 'cartasJugador',
            'allResults' => true
        ]));

        $this->setContenidoWebClan($aCoincidencias['clan']);

        $aCoincidencias['donacionesClan'] = $this->coincidenciasPatron([
            'patron' => $aPatrones['donacionesClan'],
            'clave'  => 'clan'
        ]);

        return $aCoincidencias;
    }

    private function filtrarContenidoWeb($subRutaWeb)
    {
        $url = $this->_url . $subRutaWeb;
        $contenido = strip_tags(file_get_contents($url), static::ETIQUETAS_FILTRADO);

        return $contenido;
    }

    private function coincidenciasPatron(array $config)
    {
        $clave      = $config['clave'];
        $patron     = $config['patron'];
        $allResults = isset($config['allResults']) ? $config['allResults'] : false;

        $nombreFuncion = 'getContenidoWeb' . ucfirst($clave);

        if (!$allResults) {
            preg_match($patron, $this->{$nombreFuncion}(), $coincidencias);
        } else {
            preg_match_all($patron, $this->{$nombreFuncion}(), $coincidencias);
        }

        $coincidenciasFinal = null;

        if (!empty($coincidencias)) {
            array_shift($coincidencias);

            $coincidenciasFinal = $coincidencias[0];
            $coincidenciasFinal = (!$allResults ? trim($coincidenciasFinal) : array_filter($coincidenciasFinal, 'trim'));
        }

        return $coincidenciasFinal;
    }

    ///////////////////////
}
