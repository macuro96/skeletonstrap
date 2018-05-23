<?php

namespace common\components;

use common\models\ConfigTiempoActualizado;

class ClashRoyaleData
{
    /**
     * Indica si esta en modo de depuracion.
     * @var bool
     */
    private static $_debug = false;

    /**
     * Dominio de la pagina web a la que se le va a hacer la petición para la
     * clasificación de los datos. Se obteniene desde la variable de sesión URL_DATA_CR.
     * @var string
     */
    private $_dominioUrl;

    /**
     * Url construida a partir del dominio.
     * @var string
     */
    private $_url;

    /**
     * Cadena de la respuesta a la petición de un jugador.
     * @var string
     */
    private $_contenidoWebJugador;

    /**
     * Cadena de la respuesta a la petición de un clan.
     * @var string
     */
    private $_contenidoWebClan;

    /**
     * Cadena de la respuesta a la petición de la sección de cartas de un
     * jugador en concreto.
     * @var string
     */
    private $_contenidoWebCartasJugador;

    /**
     * Array donde se almacenan los distintos patrones que se utilizarán
     * para la búsqueda de datos.
     * Su construcción debe ser igual que en la API de CR para que se pueda
     * complementar con el componente de ClashRoyaleCache de la misma forma.
     * @var array
     */
    private $_patrones;

    /**
     * Rutas de consulta para indicar que tiempo de cache de actualizado
     * añadir o actualizar.
     * @var array
     */
    private $_rutas_datos;

    /**
     * Etiquetas html que se utilizarán en el filtrado del contenido web de las
     * respuestas.
     * @var string
     */
    const ETIQUETAS_FILTRADO = '<span><div><a>';

    /**
     * Version de la API
     * @var string
     */
    const VERSION = 'macuro96-cr-api';

    /**
     * Inicia los datos iniciales: el dominio de la url, url y patrones.
     */
    public function __construct()
    {
        $this->_dominioUrl = getenv('URL_DATA_CR');
        $this->_url = 'https://' . $this->_dominioUrl . '/es/';

        $this->_rutas_datos = [
            'jugadores' => 'profile',
            'clanes' => 'clan',
            'cartasJugadores' => 'cards',
        ];

        $this->_patrones = [
            'jugador' => [
                'clan' => [
                    'tag' => '/<a href=\'https:\/\/' . $this->_dominioUrl . '\/es\/clan\/(.*?)\' class="ui__link ui__mediumText ui__whiteText profileHeader__userClan">/su',
                    // Clave clan
                    'donations' => '/<a class="ui__blueLink" href=\'https:\/\/' . $this->_dominioUrl . '\/es\/profile\/_TAG_\'>.*?<div class="clan__donation">(.*?)<\/div>/su',
                ],
                'name' => '/<span class="profileHeader__nameCaption">(.*?)<\/span>/su',
                'stats' => [
                    'level' => '/<span class="profileHeader__userLevel">(.*?)<\/span>/su',
                    'maxTrophies' => '/<div class="statistics__metricCaption ui__mediumText">Máximo de trofeos<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                    'threeCrownWins' => '/<div class="statistics__metricCaption ui__mediumText">Victorias de tres coronas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                    'totalDonations'      => '/<div class="statistics__metricCaption ui__mediumText">Donaciones totales<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                    'challengeMaxWins'     => '/<div class="statistics__metricCaption ui__mediumText">Victorias máx.<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                    'challengeCardsWon' => '/<div class="statistics__metricCaption ui__mediumText">Cartas ganadas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                    // Clave cartasJugador
                    'cardsFound' => '/<a href="https:\/\/' . $this->_dominioUrl . '\/es\/card\/(.*?)">/su'
                ],
                'trophies' => '/<div class="statistics__metricCaption ui__mediumText">Trofeos<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                'games' => [
                    'total' => '/<div class="statistics__metricCaption ui__mediumText">Partidas jugadas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                    'wins' => '/<div class="statistics__metricCaption ui__mediumText">Victorias<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su',
                    'losses' => '/<div class="statistics__metricCaption ui__mediumText">Derrotas<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su'
                ],
                // Relacion liga: ID de liga desde el nombre
                'arena' => [
                    'arenaID' => '/<div class="statistics__metricCaption ui__mediumText">Liga<\/div>[\n\r]\s+<div class="statistics__metricDots"><\/div>[\n\r]\s+<div class="statistics__metricCounter ui__headerExtraSmall">(.*?)<\/div>/su'
                ]
            ],
            'clan' => [
                'trofeos' => '',
                'donacionesSemana' => ''
            ],
        ];
    }

    ////// STATIC /////////

    /**
     * Cambia a modo depuración
     * @param bool $bDebug TRUE -> Activado
     *                     FALSE -> Por defecto, desactivado.
     */
    public static function setDebug(bool $bDebug)
    {
        static::$_debug = $bDebug;
    }

    ///////////////////////

    ////// GETTERS /////////

    /**
     * Devuelve subrutas web válidas para la consulta
     * @return array Array de subrutas
     */
    public function getRutasDatos()
    {
        return $this->_rutas_datos;
    }

    /**
     * Devuelve el contenido web guardado de un jugador.
     * Debe mantener el nombre getContenidoX para que se detecte posteriormente
     * correctamente.
     * @return string Sobre este contenido se realiza la búsqueda con patrones
     */
    public function getContenidoWebJugador()
    {
        return $this->_contenidoWebJugador;
    }

    /**
     * Devuelve el contenido web guardado de un clan.
     * Debe mantener el nombre getContenidoX para que se detecte posteriormente
     * correctamente.
     * @return string Sobre este contenido se realiza la búsqueda con patrones
     */
    public function getContenidoWebClan()
    {
        return $this->_contenidoWebClan;
    }

    /**
     * Devuelve el contenido web guardado de las cartas de un jugador concreto.
     * Debe mantener el nombre getContenidoX para que se detecte posteriormente
     * correctamente.
     * @return string Sobre este contenido se realiza la búsqueda con patrones
     */
    public function getContenidoWebCartasJugador()
    {
        return $this->_contenidoWebCartasJugador;
    }

    /**
     * Devuelve un patron dado remplazando un parametro de éste si se le indica.
     * @param  string $patron           Patrón dado de la variable privada patrones.
     * @param  string|null $parametroReplace Cadena a remplazar por el parámetro indicado
     *                                  en el patrón. Se remplaza por _TAG_.
     * @return string                   Devuelve el parametro cambiado.
     */
    public function getPatron(string $patron, ?string $parametroReplace = null)
    {
        if ($parametroReplace != null) {
             $patron = str_replace('_TAG_', $parametroReplace, $patron);
        }

        return $patron;
    }

    ////////////////////////

    ///// SETTERS /////////

    /**
     * Guarda el contenido web de un jugador al hacerle una petición al servidor X.
     * Se puede forzar a que se vuelva a guardar si se le indica con el último
     * parámetro.
     * @param string  $tag     TAG del jugador
     * @param bool    $bForzar TRUE -> Fuerza la petición al servidor.
     *                         FALSE -> Por defecto, no la fuerza, y en el caso de
     *                         que ya existan datos no hace nada.
     */
    private function setContenidoWebJugador(string $tag, bool $bForzar = false)
    {
        $subRutaWeb = $this->_rutas_datos['jugadores'] . '/' . $tag;

        if (!$this->_contenidoWebJugador || $bForzar) {
            if (!$this->actualizarDatos($subRutaWeb)) {
                $this->_contenidoWebJugador = '';
            }

            $this->_contenidoWebJugador = $this->filtrarContenidoWeb($subRutaWeb);
        }

        return $subRutaWeb;
    }

    /**
     * Guarda el contenido web de un clan al hacerle una petición al servidor X.
     * Se puede forzar a que se vuelva a guardar si se le indica con el último
     * parámetro.
     * @param string  $tag     TAG del clan
     * @param bool    $bForzar TRUE -> Fuerza la petición al servidor.
     *                         FALSE -> Por defecto, no la fuerza, y en el caso de
     *                         que ya existan datos no hace nada.
     */
    private function setContenidoWebClan(string $tag, bool $bForzar = false)
    {
        $subRutaWeb = $this->_rutas_datos['clanes'] . '/' . $tag;

        if (!$this->_contenidoWebClan || $bForzar) {
            if (!$this->actualizarDatos($subRutaWeb)) {
                $this->_contenidoWebClan = '';
            }

            $this->_contenidoWebClan = $this->filtrarContenidoWeb($subRutaWeb);
        }

        return $subRutaWeb;
    }

    /**
     * Guarda el contenido web de las cartas de un jugador al hacerle una petición al servidor X.
     * Se puede forzar a que se vuelva a guardar si se le indica con el último
     * parámetro.
     * @param string  $tagJugador  TAG del jugador
     * @param bool    $bForzar     TRUE -> Fuerza la petición al servidor.
     *                             FALSE -> Por defecto, no la fuerza, y en el caso de
     *                             que ya existan datos no hace nada.
     */
    private function setContenidoWebCartasJugador(string $tagJugador, bool $bForzar = false)
    {
        $subRutaWeb = $this->_rutas_datos['jugadores'] . '/' . $tagJugador . '/' .  $this->_rutas_datos['cartasJugadores'];
        $subRutaRefresh = $this->_rutas_datos['jugadores'] . '/' . $tagJugador . '/' .  $this->_rutas_datos['jugadores'];

        if (!$this->_contenidoWebCartasJugador || $bForzar) {
            if (!$this->_contenidoWebJugador && !$this->actualizarDatos($subRutaRefresh)) {
                $this->_contenidoWebCartasJugador = '';
            }

            $this->_contenidoWebCartasJugador = $this->filtrarContenidoWeb($subRutaWeb);
        }

        return $subRutaWeb;
    }

    ////////////////////////

    ///// METODOS /////////

    /**
     * Getter de la versión de la API
     * @return string Devuelve la version
     */
    public function version()
    {
        return self::VERSION;
    }

    /**
     * Busca los jugadores en forma de array de objetos para su posterior
     * procesamiento donde sea necesario.
     * @param  array  $tagsJugadores TAGS de los jugadores buscados
     * @return array  Devuelve los jugadores en forma de array de objetos.
     */
    public function jugadores(array $tagsJugadores)
    {
        $aResultado = [];

        $comprobarNull = function ($valor) {
            return $valor === null;
        };

        $nJugadores     = count($tagsJugadores);
        $aCoincidencias = null;
        $subRutasWeb    = [];

        $clan = null;

        for ($j = 0; $j < $nJugadores; $j++) {
            $bClaveCoincidenciaNotFound = true;

            $aCoincidencias      = null;
            $clavesCheckNotFound = [];

            $subRutasWeb[$j][] = $this->setContenidoWebJugador($tagsJugadores[$j], true);
            $subRutasWeb[$j][] = $this->setContenidoWebCartasJugador($tagsJugadores[$j], true);

            $clavePatron = 'jugador';

            $patron = $this->getPatron($this->_patrones[$clavePatron]['clan']['tag']);

            $aCoincidencias[$j]['clan']['tag'] = $this->coincidenciasPatron([
                'patron' => $patron,
                'clave'  => $clavePatron
            ]);

            $clanTemp = $aCoincidencias[$j]['clan']['tag'];
            $clavesCheckNotFound[] = $comprobarNull($clanTemp);

            if ($clan == null) {
                $clan = $clanTemp;
            }

            $bForzar = ($clanTemp != $clan);

            $subRutasWeb[$j][] = $this->setContenidoWebClan($clanTemp, $bForzar);

            $patron = $this->getPatron($this->_patrones[$clavePatron]['clan']['donations'], $tagsJugadores[$j]);
            $aCoincidencias[$j]['clan']['donations'] = $this->coincidenciasPatron([
                'patron' => $patron,
                'clave'  => 'clan'
            ]);
            $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['clan']['donations']);

            $patron = $this->getPatron($this->_patrones[$clavePatron]['name']);
            $aCoincidencias[$j]['name'] = $this->coincidenciasPatron([
                'patron' => $patron,
                'clave'  => $clavePatron
            ]);
            $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['name']);

            $aStats = [
                'level',
                'maxTrophies',
                'threeCrownWins',
                'totalDonations',
                'challengeMaxWins',
                'challengeCardsWon',
            ];

            for ($i = 0; $i < count($aStats); $i++) {
                $patron = $this->getPatron($this->_patrones[$clavePatron]['stats'][$aStats[$i]]);

                if ($aStats[$i] == 'challengeCardsWon') {
                    $aCoincidencias[$j]['stats'][$aStats[$i]] = $this->coincidenciasPatron([
                        'patron' => $patron,
                        'clave' => $clavePatron,
                        'allResults' => true
                    ])[1];
                } else {
                    $aCoincidencias[$j]['stats'][$aStats[$i]] = $this->coincidenciasPatron([
                        'patron' => $patron,
                        'clave' => $clavePatron,
                    ]);
                }

                $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['stats'][$aStats[$i]]);
            }

            $patron = $this->getPatron($this->_patrones[$clavePatron]['stats']['cardsFound'], $tagsJugadores[$j]);
            $aCoincidencias[$j]['stats']['cardsFound'] = count($this->coincidenciasPatron([
                'patron' => $patron,
                'clave'  => 'cartasJugador',
                'allResults' => true
            ]));
            $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['stats']['cardsFound']);

            $patron = $this->getPatron($this->_patrones[$clavePatron]['trophies']);
            $aCoincidencias[$j]['trophies'] = $this->coincidenciasPatron([
                'patron' => $patron,
                'clave'  => $clavePatron
            ]);
            $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['trophies']);

            $aGames = [
                'total',
                'wins',
                'losses',
            ];

            for ($i = 0; $i < count($aGames); $i++) {
                $patron = $this->getPatron($this->_patrones[$clavePatron]['games'][$aGames[$i]]);
                $aCoincidencias[$j]['games'][$aGames[$i]] = $this->coincidenciasPatron([
                    'patron' => $patron,
                    'clave' => $clavePatron
                ]);
                $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['games'][$aGames[$i]]);
            }

            $empates = $aCoincidencias[$j]['games']['total'] - ($aCoincidencias[$j]['games']['wins'] + $aCoincidencias[$j]['games']['losses']);
            $aCoincidencias[$j]['games']['draws'] = $empates;
            $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['games']['draws']);

            $patron = $this->getPatron($this->_patrones[$clavePatron]['arena']['arenaID']);
            $nombreArena = $this->coincidenciasPatron([
                'patron' => $patron,
                'clave'  => $clavePatron
            ]);
            $clavesCheckNotFound[] = $comprobarNull($nombreArena);

            $aCoincidencias[$j]['arena']['arenaID'] = $this->findDatoRelacion('\common\models\Ligas', 'id', ['nombre' => $nombreArena]);
            $aCoincidencias[$j]['tag'] = $tagsJugadores[$j];

            $clavesCheckNotFound[] = $comprobarNull($aCoincidencias[$j]['arena']['arenaID']);

            $bClaveCoincidenciaNotFound = in_array(true, $clavesCheckNotFound);

            if (!$bClaveCoincidenciaNotFound) {
                $aCoincidencias[$j]        = (object) $aCoincidencias[$j];
                $aCoincidencias[$j]->clan  = (object) $aCoincidencias[$j]->clan;
                $aCoincidencias[$j]->stats = (object) $aCoincidencias[$j]->stats;
                $aCoincidencias[$j]->games = (object) $aCoincidencias[$j]->games;
                $aCoincidencias[$j]->arena = (object) $aCoincidencias[$j]->arena;
            }

            $aResultado[] = is_array($aCoincidencias) ? $aCoincidencias[$j] : [];
        }

        return $aResultado;
    }

    /**
     * Busca un dato que no se puede recopilar directamente desde el contenido
     * web. Tiene que tener una referencia en la BD para que la búsqueda se
     * haga correctamente.
     * @param  string $relacion Nombre de la clase a la que se va a hacer referencia.
     * @param  string $campo    Nombre del campo que se va a devolver.
     * @param  array  $busqueda Array de clave-valor de un único elemento por el que se
     *                          va a filtrar la referencia de la búsqueda.
     * @return mixed            Devuelve el dato que se busca por referencia indirecta.
     */
    private function findDatoRelacion(string $relacion, string $campo, array $busqueda)
    {
        $objTemp = new $relacion;

        $clave = array_keys($busqueda)[0];
        $valor = array_values($busqueda)[0];

        $dato = get_class($objTemp)::find()
                                     ->select($campo)
                                     ->where([$clave => $valor])
                                     ->scalar();

        return $dato;
    }

    /**
     * Filtra el contenido web de las peticiones con las etiquetas html de filtrado
     * anteriormente definidas.
     * @param  string $subRutaWeb Porción de la URL de consulta por la que se va realizar
     *                            la petición.
     * @return string             Devuelve el contenido filtrado.
     */
    private function filtrarContenidoWeb(string $subRutaWeb)
    {
        $url = $this->_url . $subRutaWeb;
        $contenido = strip_tags(file_get_contents($url), static::ETIQUETAS_FILTRADO);

        return $contenido;
    }

    /**
     * Realiza un filtrado a través del patrón proporcionado en un contenido
     * web predefinido.
     * @param  array  $config Array de configuracion:
     *                        - string clave (una clave de _patrones)
     *                        - string patron (un valor de _patrones)
     *                        - bool   allResults (devuelve todos los resultados o no)
     * @return array|null     Devuelve las coincidencias encontradas aplicando el patrón sobre el contenido web.
     */
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

    /**
     * Actualiza los datos de la página web de la que se recibe los datos y el registro de cacheado si es posible.
     * @param  string $subRutaWeb Subrutaweb válida
     * @return bool|null          TRUE -> se ha actualizado correctamente.
     *                            FALSE -> no se ha podido actualizar (puede ser que ya esté actualizado recientemente)
     *                            NULL -> error.
     */
    public function actualizarDatos(string $subRutaWeb)
    {
        $url = $this->_url . $subRutaWeb . '/refresh';

        $actualizado = ConfigTiempoActualizado::actualizarTiempoCache($subRutaWeb, function () use ($url) {
            $contenido = json_decode(file_get_contents($url));
            return $contenido->success;
        });

        return $actualizado;
    }

    ///////////////////////
}
