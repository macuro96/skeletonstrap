<?php

namespace common\models;

use yii\web\BadRequestHttpException;

use yii\base\Model;

abstract class ClashRoyaleCache extends \yii\db\ActiveRecord
{
    /**
     * Implementar de manera que devuelva un array con las claves que se quieren buscar
     * en el modelo de datos json de la api.
     * Para poder buscar recursivamente entre objetos json hay que hacer separaciones con
     * el delimitador ".". Ejemplo: stats.level
     * @var array
     */
    abstract public static function clavesCache();

    /**
     * Debe devolver las claves de los labels del modelo.
     * @var array
     */
    abstract public static function clavesLabelsStatic();

    /**
     * Debe devolver la combinacion del array que devuelve el método clavesLabelsStatic() y
     * los nombres de los valores de los labels del modelo.
     * @var array
     */
    abstract public static function attributeLabelsStatic();

    /**
     * Hace una búsqueda de datos en la BD local o a través del componente ClashRoyaleAPI dependiendo
     * si ya existen previamente los datos o no. Se puede forzar la búsqueda con el último parámetro.
     * @param  string  $metodo           Método para la búsqueda. Debe de existir en el componente ClashRoyaleAPI.
     * @param  string  $claveBusquedaAPI Clave de búsqueda
     * @param  string  $valorBusquedaAPI Valor para la búsqueda
     * @param  bool    $bForzarBusqueda  TRUE  -> Fuerza la búsqueda aunque ya existan los datos.
     *                                   FALSE -> Por defecto, solo hace la búsqueda si no existen datos.
     * @return Model                     Devuelve un modelo distinto dependiendo de los datos que se busquen.
     */
    public static function findOneAPI(string $metodo, string $claveBusquedaAPI, string $valorBusquedaAPI, bool $bForzarBusqueda = false)
    {
        $jugador = self::findOne([$claveBusquedaAPI => $valorBusquedaAPI]);
        $model   = $jugador;

        if ($jugador === null || $bForzarBusqueda) {
            $api = \Yii::$app->crapi;
            //$atributosJSON = $api->jugador($valorBusquedaAPI);
            $atributosJSON = $api->{$metodo}($valorBusquedaAPI);

            $atributosLabelsStatic = static::attributeLabelsStatic();
            $clavesCache = static::clavesCache();

            foreach ($clavesCache as $key => $value) {
                $claveTemp = $key;
                $separacionClave = explode('.', $claveTemp);

                $nObjetos = count($separacionClave);

                $claveValorAtributo = $separacionClave;

                $claveAtributo = $value;
                $valorAtributo = isset($atributosJSON->{$separacionClave[0]}) ? $atributosJSON->{$separacionClave[0]} : null;

                if ($valorAtributo !== null) {
                    if ($nObjetos > 1) {
                        for ($o = 1; $o < $nObjetos; $o++) {
                            $valorAtributo = $valorAtributo->{$separacionClave[$o]};
                        }
                    }

                    $atributos[$claveAtributo] = $valorAtributo;
                }
            }

            if ($jugador === null) {
                $nombreClase = self::className();
                $model = new $nombreClase($atributos);
            } else {
                foreach ($atributos as $key => $value) {
                    $model[$key] = $value;
                }
            }

            if (!$model->save()) {
                throw new BadRequestHttpException('No se ha podido realizar la búsqueda de algunos datos correctamente.');
            }

            $model->refresh();
        }

        return $model;
    }
}
