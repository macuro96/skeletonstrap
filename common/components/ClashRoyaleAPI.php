<?php

namespace common\components;

use yii\web\Response;

use yii\base\Component;
use yii\web\BadRequestHttpException;

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
        } else if (YII_ENV == 'dev') {
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
     * @param  string $url Url final que se forma con los distintos métodos de la clase.
     * @return mixed Devuelve los datos en forma de array json, o nulo si ha ocurrido un error.
     */
    private function conexion(string $url)
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

        $jsonData = json_decode($data);

        if ($jsonData === null) {
            return $jsonData;
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $jsonData;
    }

    /**
     * Realiza la búsqueda de un equipo (clan) de CR válido
     * @param  string $tag El TAG del equipo real de CR
     * @return mixed      Devuelve los datos de un clan de CR
     */
    public function clan(string $tag)
    {
        $endpoint = 'clan';

        $url = $this->_url . "$endpoint/$tag"; //?exclude=members

        $this->validarConexion($endpoint, [$tag]);

        return $this->conexion($url);
    }

    /**
     * Realiza la búsqueda de un jugador de CR válido
     * @param  string $tag El TAG del jugador real de CR
     * @return mixed      Devuelve los datos de un jugador de CR
     */
    public function jugador($tag)
    {
        $endpoint = 'player';

        $keys = implode(',', [
            'tag',
            'name',
            'trophies',
            'arena',
            'clan',
            'stats',
            'games'
        ]);

        $url = $this->_url . "$endpoint/$tag?keys=$keys";

        $this->validarConexion($endpoint, [$tag]);

        return $this->conexion($url);
    }

    /**
     * Realiza la búsqueda varios jugadores de CR válidos
     * @param  array $tags Un array con los TAGS de los jugadores reales de CR
     *                     que se quieran buscar.
     * @return mixed      Devuelve los datos varios jugadores de CR
     */
    public function jugadores(array $tags)
    {
        $endpoint = 'player';

        $keys = implode(',', [
            'tag',
            'name',
            'trophies',
            'arena',
            'clan',
            'stats',
            'games'
        ]);

        $sTags = implode(',', $tags);

        $url = $this->_url . "$endpoint/$sTags?keys=$keys";

        $this->validarConexion($endpoint, $tags);

        return $this->conexion($url);
    }
}
