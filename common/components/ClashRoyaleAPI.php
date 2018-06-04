<?php

namespace common\components;

use yii\base\Component;
use yii\web\BadRequestHttpException;

use common\models\ConfigTiempoActualizado;

/**
 * Componente común:
 * API de ClashRoyale (en desarrollo)
 */
class ClashRoyaleAPI extends Component
{
    /**
     * Key developer de la api https://api.royaleapi.com/
     * Se accede a través de una variable de entorno del sistema
     * @var string
     */
    private $_key;

    /**
     * Dirección url donde se encuentra la aplicación REST de CR
     * @var string
     */
    private $_url;

    /**
     * Endpoints para la utilización de la aplicación REST validos.
     * Solo se pueden utilizar los que se almacenen en este array.
     * @var array
     */
    private $_endpointsValidos;

    /**
     * Define si esta en modo debug o no. Proporciona información adicional
     * en algunas partes del código.
     * @var bool
     */
    private $_debug;

    /**
     * Rutas de consulta para indicar que tiempo de cache de actualizado
     * añadir o actualizar.
     * @var array
     */
    private $_rutas_datos;

    public function __construct()
    {
        $this->setDebug(false);
        $this->_key = getenv('KEY_CR_API');
        $this->_url = 'https://api.royaleapi.com/';
        $this->_endpointsValidos = [
            'clan',
            'player',
            'battles'
        ];

        $this->_rutas_datos = [
            'jugador' => 'profile',
            'clan' => 'clan',
            'cartasJugador' => 'cards',
        ];
    }

    /**
     * Indica el modo debug
     * @param bool $bModo TRUE  -> Activado
     *                    FALSE -> Por defecto, Desactivado
     */
    public function setDebug(bool $bModo)
    {
        $this->_debug = $bModo;
    }

    /**
     * Devuelve subrutas web válidas para la consulta
     * @return array Array de subrutas
     */
    public function getRutasDatos()
    {
        return $this->_rutas_datos;
    }

    /**
     * Comprueba si la conexión que se va a realizar con la api es correcta.
     * @param  string $endpoint          Endpoint valido (definidos en el constructor)
     * @param  array  $tags              Tag
     * @param  string|null $endpointAdicional Endpoint válido adicional
     * @return BadRequestHttpException Solo devuelve la excepción si la petición no es válida.
     */
    private function validarConexion(string $endpoint, array $tags, ?string $endpointAdicional = null)
    {
        $nTags = count($tags);
        $bEPsValidos = in_array($endpoint, $this->_endpointsValidos) && (in_array($endpointAdicional, $this->_endpointsValidos) || $endpointAdicional === null);
        $bKey = ($this->_key !== null);

        if (YII_ENV == 'prod') {
            if ($nTags > 7 || $nTags == 0 || !$bEPsValidos || !$bKey) {
                throw new BadRequestHttpException('Petición no realizada correctamente');
            }
        } elseif (YII_ENV == 'dev') {
            $errorsTemp = [];

            if ($nTags > 7) {
                $errorsTemp[] = 'no puede haber más de 7 tags';
            }

            if ($nTags == 0) {
                $errorsTemp[] = 'no puede haber 0 tags';
            }

            if (!$bEPsValidos) {
                $errorsTemp[] = 'endpoints no validos (' . $endpoint . ', ' . $endpointAdicional . ')';
            }

            if (!$bKey) {
                $errorsTemp[] = 'key no válida';
            }

            if (!empty($errorsTemp)) {
                throw new BadRequestHttpException(implode($errorsTemp, ', '));
            }
        }
    }

    /**
     * Realiza la conexión con la aplicación REST de CR
     * @param  string $url       Url final que se forma con los distintos métodos de la clase.
     * @param  bool   $bDataJSON Devolver los datos en JSON o no. Por defecto es TRUE.
     * @return mixed Devuelve los datos en forma de array json, o nulo si ha ocurrido un error.
     */
    private function conexion(string $url, bool $bDataJSON = true)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'auth: ' . $this->_key
        ]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $data = curl_exec($ch);
        curl_close($ch);

        if ($this->_debug) {
            var_dump('debug:');
            var_dump($data);
            die();
        }

        if (!$bDataJSON) {
            return $data;
        }

        $jsonData = json_decode($data);

        if ($jsonData == null) {
            return null;
        }

        return $jsonData;
    }

    /**
     * Devuelve la versión de la API.
     * @return string|null Si ha podido hacer la conexión devuelve la versión,
     *                     y en el caso de que no devuelve un null.
     */
    public function version()
    {
        $endpoint = 'version';

        $url = $this->_url . "$endpoint";

        return $this->conexion($url, false);
    }

    /**
     * Realiza la búsqueda de uno o más equipos (clan) de CR válido
     * @param  array $tags El TAG del equipo real de CR
     * @return mixed       Devuelve los datos de un clan de CR
     */
    public function clan(array $tags)
    {
        $endpoint = 'clan';
        $datos    = null;

        $keys = implode(',', [
            'tag',
            'name',
            'description',
            'score',
            'memberCount',
            'requiredScore',
            'donations'
        ]);

        if (!empty($tags)) {
            $sTags = implode(',', $tags);
            $url = $this->_url . "$endpoint/$sTags?keys=$keys";

            $this->validarConexion($endpoint, $tags);
            $datos = $this->conexion($url);
        }

        if ($datos && (isset($datos->error) ? !$datos->error : true)) {
            foreach ($tags as $tag) {
                $subRutaWebClan = $this->_rutas_datos['clan'] . '/' . $tag;
                $this->actualizarDatos($subRutaWebClan);
            }
        }

        return $datos;
    }

    /**
     * Realiza la búsqueda varios jugadores de CR válidos
     * @param  array $tags Un array con los TAGS de los jugadores reales de CR
     *                     que se quieran buscar.
     * @return mixed      Devuelve los datos varios jugadores de CR
     */
    public function jugador(array $tags)
    {
        $endpoint = 'player';
        $datos    = null;

        $keys = implode(',', [
            'tag',
            'name',
            'trophies',
            'arena',
            'clan',
            'stats',
            'games'
        ]);

        if (!empty($tags)) {
            $sTags = implode(',', $tags);
            $url = $this->_url . "$endpoint/$sTags?keys=$keys";

            $this->validarConexion($endpoint, $tags);
            $datos = $this->conexion($url);
        }

        if ($datos && (isset($datos->error) ? !$datos->error : true)) {
            $contador = 0;

            foreach ($tags as $tag) {
                $subRutaWebJugador = $this->_rutas_datos['jugador'] . '/' . $tag;
                $this->actualizarDatos($subRutaWebJugador);

                if (is_array($datos)) {
                    if (isset($datos[$contador]->clan->tag)) {
                        $subRutaWebClan = $this->_rutas_datos['clan'] . '/' . $datos[$contador]->clan->tag;
                        $this->actualizarDatos($subRutaWebClan);
                    }
                } else {
                    if (isset($datos->clan->tag)) {
                        $subRutaWebClan = $this->_rutas_datos['clan'] . '/' . $datos->clan->tag;
                        $this->actualizarDatos($subRutaWebClan);
                    }
                }

                $contador++;
            }
        }

        return $datos;
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
        return ConfigTiempoActualizado::actualizarTiempoCache($subRutaWeb);
    }
}
