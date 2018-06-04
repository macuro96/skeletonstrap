<?php

namespace common\components;

use common\models\ConfigTiempoActualizado;

/**
 * Componente común:
 * API propia para recuperar distintos datos de ClashRoyale y devolverlos en el mismo
 * formato que la original para poder complementarse. Se utiliza automaticamente en el caso
 * de que el primer componente (API original) falle.
 */
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
     * Contenido web de cada ruta de datos
     * @var array
     */
    private $_contenidoWeb;

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
            'jugador' => 'profile',
            'clan' => 'clan',
            'cartasJugador' => 'cards',
        ];

        foreach ($this->_rutas_datos as $key => $value) {
            $this->_contenidoWeb[$key] = '';
        }

        $this->_patrones = [
            'jugador' => [
                'clan' => [
                    'tag' => '/<a href=\'https:\/\/' . $this->_dominioUrl . '\/es\/clan\/(.*?)\' class="ui__link ui__mediumText ui__whiteText profileHeader__userClan">/su',
                    // Clave clan
                    'donations' => '/<a class="ui__blueLink" href=\'https:\/\/' . $this->_dominioUrl . '\/es\/profile\/_TAG_\'>.*?<div class="clan__donation">(.*?)<\/div>/su',
                    'role' => '/<a class="ui__blueLink" href=\'https:\/\/' . $this->_dominioUrl . '\/es\/profile\/_TAG_\'>.*?<div class="clan__memberRoleInner">(.*?)<\/div>/su'
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
     * Devuelve el contenido web guardado de una clave de rutas de datos predefinida.
     * @param string $clave clave por la que buscar en las rutas de datos
     * @return string Sobre este contenido se realiza la búsqueda con patrones
     */
    public function getContenidoWeb(string $clave)
    {
        return $this->_contenidoWeb[$clave];
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
     * Guarda el contenido web al hacerle una petición al servidor X.
     * Se puede forzar a que se vuelva a guardar si se le indica con el último
     * parámetro.
     * @param string       $claveSubRutaWeb Clave de la subruta con la que hacer la petición
     * @param string|null  $tag     TAG
     * @param bool         $bForzar TRUE -> Fuerza la petición al servidor.
     *                              FALSE -> Por defecto, no la fuerza, y en el caso de
     *                              que ya existan datos no hace nada.
     * @param array $config         Configuracion (claveSubRutaWeb2)
     */
    private function setContenidoWeb(string $claveSubRutaWeb, ?string $tag, bool $bForzar, array $config = [])
    {
        if ($tag == null) {
            return null;
        }

        $claveSubRutaWeb2 = isset($config['claveSubRutaWeb2']) ? $config['claveSubRutaWeb2'] : '';

        $subRutaWeb  = $this->_rutas_datos[$claveSubRutaWeb] . '/' . $tag;
        $subRutaWeb2 = $subRutaWeb . ($claveSubRutaWeb2 ? ('/' . $this->_rutas_datos[$claveSubRutaWeb2]) : '');

        $contenido = ($claveSubRutaWeb2 ? $this->getContenidoWeb($claveSubRutaWeb2) : $this->getContenidoWeb($claveSubRutaWeb));

        if (!$contenido || $bForzar) {
            $indiceClaveContenidoWeb = $claveSubRutaWeb;

            if ($claveSubRutaWeb2 && $this->getContenidoWeb($claveSubRutaWeb)) {
                $this->_contenidoWeb[$claveSubRutaWeb2] = $this->filtrarContenidoWeb($subRutaWeb2);
            } elseif (!$claveSubRutaWeb2) {
                $this->actualizarDatos($subRutaWeb);
                $this->_contenidoWeb[$claveSubRutaWeb] = $this->filtrarContenidoWeb($subRutaWeb);
            }
        }

        return ($claveSubRutaWeb2 ? $subRutaWeb2 : $subRutaWeb);
    }

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
        return $this->setContenidoWeb('jugador', $tag, $bForzar);
    }

    /**
     * Guarda el contenido web de un clan al hacerle una petición al servidor X.
     * Se puede forzar a que se vuelva a guardar si se le indica con el último
     * parámetro.
     * @param string|null  $tag     TAG del clan
     * @param bool         $bForzar TRUE -> Fuerza la petición al servidor.
     *                              FALSE -> Por defecto, no la fuerza, y en el caso de
     *                              que ya existan datos no hace nada.
     */
    private function setContenidoWebClan(?string $tag, bool $bForzar = false)
    {
        return $this->setContenidoWeb('clan', $tag, $bForzar);
    }

    /**
     * Guarda el contenido web de las cartas de un jugador al hacerle una petición al servidor X.
     * Se puede forzar a que se vuelva a guardar si se le indica con el último
     * parámetro.
     * @param string|null  $tagJugador  TAG del jugador
     * @param bool         $bForzar     TRUE -> Fuerza la petición al servidor.
     *                                  FALSE -> Por defecto, no la fuerza, y en el caso de
     *                                  que ya existan datos no hace nada.
     */
    private function setContenidoWebCartasJugador(?string $tagJugador, bool $bForzar = false)
    {
        return $this->setContenidoWeb('jugador', $tagJugador, $bForzar, [
            'claveSubRutaWeb2' => 'cartasJugador',
        ]);
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
    public function jugador(array $tagsJugadores)
    {
        $aResultado = [];
        $nJugadores = count($tagsJugadores);

        $aStats = [
            'level',
            'maxTrophies',
            'threeCrownWins',
            'totalDonations',
            'challengeMaxWins',
            //'challengeCardsWon',
        ];

        $aGames = [
            'total',
            'wins',
            'losses',
        ];

        $clan = null;

        for ($j = 0; $j < $nJugadores; $j++) {
            $aCoincidencias = [];
            $subRutasWeb    = [];

            $fSegundoResultado = function (&$coincidencia) {
                $coincidencia = (isset($coincidencia[1]) ? $coincidencia[1] : null);
            };

            $fContarResultados = function (&$coincidencia) {
                $coincidencia = count($coincidencia);
            };

            $subRutasWeb[$j][] = $this->setContenidoWebJugador($tagsJugadores[$j], true);
            $subRutasWeb[$j][] = $this->setContenidoWebCartasJugador($tagsJugadores[$j], true);

            $clavePatron = 'jugador';

            $clanTemp = $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'clan', 'tag');

            if ($clan == null) {
                $clan = $clanTemp;
            }

            $bForzar = ($clanTemp != $clan);

            $subRutasWeb[$j][] = $this->setContenidoWebClan($clan, $bForzar);

            $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'clan', 'donations', [
                'clave' => 'clan',
                'parametroPatron' => $tagsJugadores[$j]
            ]);

            $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'clan', 'role', [
                'clave' => 'clan',
                'parametroPatron' => $tagsJugadores[$j]
            ]);

            $this->coincidenciaPrimerNivel($aCoincidencias, $clavePatron, 'name');

            for ($i = 0; $i < count($aStats); $i++) {
                $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'stats', $aStats[$i]);
            }

            $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'stats', 'challengeCardsWon', [
                'allResults' => true,
                'function' => $fSegundoResultado
            ]);

            $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'stats', 'cardsFound', [
                'clave' => 'cartasJugador',
                'allResults' => true,
                'parametroPatron' => $tagsJugadores[$j],
                'function' => $fContarResultados
            ]);

            $this->coincidenciaPrimerNivel($aCoincidencias, $clavePatron, 'trophies');

            for ($i = 0; $i < count($aGames); $i++) {
                $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'games', $aGames[$i]);
            }

            $empates = $aCoincidencias['games']['total'] - ($aCoincidencias['games']['wins'] + $aCoincidencias['games']['losses']);
            $aCoincidencias['games']['draws'] = $empates;

            $nombreArena = $this->coincidenciaSegundoNivel($aCoincidencias, $clavePatron, 'arena', 'arenaID');

            $aCoincidencias['arena']['arenaID'] = $this->findDatoRelacion('\common\models\Ligas', 'id', ['nombre' => $nombreArena]);
            $aCoincidencias['tag'] = $tagsJugadores[$j];

            $aCoincidencias        = (object) $aCoincidencias;
            $aCoincidencias->clan  = (object) $aCoincidencias->clan;
            $aCoincidencias->stats = (object) $aCoincidencias->stats;
            $aCoincidencias->games = (object) $aCoincidencias->games;
            $aCoincidencias->arena = (object) $aCoincidencias->arena;

            $aResultado[] = $aCoincidencias;
        }

        return $aResultado;
    }

    /**
     * Busca coincidencias de forma general con un patrón sobre una cadena previamente recogida
     * con la funcion de setContenidoWeb.
     * @param  string|null &$patron        Donde se va a guardar el patron.
     * @param  string|null $clavePatron    Clave del patron.
     * @param  string|null $clave          Clave para el array, puede ser nulo si es de primer nivel.
     * @param  string|null $valor          Valor para el array.
     * @param  array|null  &$config        Donde se va a guardar la configuracion (patron, clave).
     * @param  array|null  $configAnadido  Configuracion añadida o sobrescribir la existente en $config (patron, clave, allResults, parametroPatron y function).
     */
    private function coincidencia(?string &$patron, ?string $clavePatron, ?string $clave, ?string $valor, ?array &$config, ?array $configAnadido)
    {
        $parametroPatron = (isset($configAnadido['parametroPatron']) ? $configAnadido['parametroPatron'] : null);

        if ($parametroPatron) {
            unset($configAnadido['parametroPatron']);
        }

        if ($clave) {
            $patron = $this->getPatron($this->_patrones[$clavePatron][$clave][$valor], $parametroPatron);
        } else {
            $patron = $this->getPatron($this->_patrones[$clavePatron][$valor], $parametroPatron);
        }

        $config = array_merge([
            'patron' => $patron,
            'clave'  => $clavePatron
        ], $configAnadido);
    }

    /**
     * Coincidencias de patron de primer nivel (solo valor)
     * @param  array|null  &$aCoincidencias Donde se almaacenan las coincidencias
     * @param  string|null $clavePatron     Clave del patron
     * @param  string|null $valor           Valor a buscar en el patron
     * @param  array|null  $configAnadido   Configuracion añadida
     * @return mixed                        Devuelve la coincidencia
     */
    private function coincidenciaPrimerNivel(?array &$aCoincidencias, ?string $clavePatron, ?string $valor, ?array $configAnadido = [])
    {
        $allResults = isset($configAnadido['allResults']) ? true : false;
        $function   = isset($configAnadido['function']) ? $configAnadido['function'] : null;

        $this->coincidencia($patron, $clavePatron, null, $valor, $config, $configAnadido);
        $coincidencia = $this->coincidenciasPatron($config);

        if ($function && $coincidencia !== null) {
            $function($coincidencia);
        }

        $aCoincidencias[$valor] = $coincidencia;

        return $coincidencia;
    }

    /**
     * Coincidencias de patron de primer nivel (solo valor)
     * @param  array|null  &$aCoincidencias Donde se almaacenan las coincidencias
     * @param  string|null $clavePatron     Clave del patron
     * @param  string|null $clave           Clave del valor a buscar
     * @param  string|null $valor           Valor a buscar en el patron
     * @param  array|null  $configAnadido   Configuracion añadida
     * @return mixed                        Devuelve la coincidencia
     */
    private function coincidenciaSegundoNivel(?array &$aCoincidencias, ?string $clavePatron, ?string $clave, ?string $valor, ?array $configAnadido = [])
    {
        $allResults = isset($configAnadido['allResults']) ? true : false;
        $function   = isset($configAnadido['function']) ? $configAnadido['function'] : null;

        $this->coincidencia($patron, $clavePatron, $clave, $valor, $config, $configAnadido);
        $coincidencia = $this->coincidenciasPatron($config);

        if ($function && $coincidencia !== null) {
            $function($coincidencia);
        }

        $aCoincidencias[$clave][$valor] = $coincidencia;

        return $coincidencia;
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

        $nombreFuncion = 'getContenidoWeb';

        if (!$allResults) {
            preg_match($patron, $this->{$nombreFuncion}($clave), $coincidencias);
        } else {
            preg_match_all($patron, $this->{$nombreFuncion}($clave), $coincidencias);
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
