<?php

namespace common\models;

use yii\db\Expression;

/**
 * This is the model class for table "config_tiempo_actualizado".
 *
 * @property int $id
 * @property string $subrutaweb
 * @property string $created_at
 */
class ConfigTiempoActualizado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config_tiempo_actualizado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subrutaweb'], 'required'],
            [['created_at'], 'safe'],
            [['subrutaweb'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subrutaweb' => 'Subrutaweb',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Actualiza el tiempo de cacheado según una subruta de consulta válida y una
     * función dada por el usuario.
     * @param  string $subRutaWeb Subruta valida de la lista de los componentes de
     *                            la API.
     * @param  mixed $function    Función para que devuelva TRUE o FALSE que indique
     *                            si se puede realizar la actualización o no. Puede ser
     *                            null en caso de que no necesite ninguna condición.
     * @return bool|null          Devuelve un booleano dependiendo si se ha podido actualizar
     *                            el registro o no. En el caso de que ocurra un error (como por
     *                            ejemplo que falle la condición de la función) devuelve un null.
     */
    public static function actualizarTiempoCache(string $subRutaWeb, $function = null)
    {
        $actualizado = false;

        if (self::find()->where(['subrutaweb' => $subRutaWeb])->one() === null) {
            $bFunction = ($function !== null ? $function() : true);

            if ($bFunction) {
                $registro = new self([
                    'subrutaweb' => $subRutaWeb
                ]);

                $actualizado = $registro->save();
            } else {
                $actualizado = null;
            }
        }

        return $actualizado;
    }

    /**
     * Borra los registros que tengan más de 14 minutos desde su creación
     */
    public static function clearRegistros()
    {
        self::deleteAll(new Expression("((current_timestamp - created_at) > interval '14 min')"));
    }

    public static function ultimaActualizacionJugador($tag)
    {
        $subRutaWeb = 'profile/' . $tag;

        return self::find()
                   ->where(new Expression("((current_timestamp - created_at) > interval '14 min')"))
                   ->andWhere(['subrutaweb' => $subRutaWeb])
                   ->one();
    }
}
