<?php

namespace common\models;

use Yii;

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

    public static function actualizarTiempoCache($subRutaWeb, $callBackRefresh = null)
    {
        $bActualizado = false;

        if (self::find()->where(['subrutaweb' => $subRutaWeb])->one() === null) {
            $bRefresh = ($callBackRefresh !== null ? $callBackRefresh() : true);

            $registro = new self([
                'subrutaweb' => $subRutaWeb
            ]);

            $bActualizado = $registro->save();
        }

        return $bActualizado;
    }

    public static function clearRegistros()
    {
        self::deleteAll(new Expression("((current_timestamp - created_at) > interval '14 min')"));
    }
}
