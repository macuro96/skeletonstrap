<?php

namespace common\components;

use yii\web\Response;

use yii\base\Component;
use yii\web\BadRequestHttpException;

class ClashRoyaleAPI extends Component
{
    private $_key;
    private $_url;
    private $_endpointsValidos;

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

    public function setDebug(bool $bModo)
    {
        $this->_debug = $bModo;
    }

    private function validarConexion($endpoint, $tags, $endpointAdicional = null)
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

    private function conexion($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'auth: ' . $this->_key
        ]);

        $data = curl_exec($ch);
        curl_close($ch);

        if ($this->_debug) {
            return json_decode($data);
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $data;
    }

    public function clan($tag)
    {
        $endpoint = 'clan';

        $url = $this->_url . "$endpoint/$tag"; //?exclude=members

        $this->validarConexion($endpoint, [$tag]);

        return $this->conexion($url);
    }

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
}
