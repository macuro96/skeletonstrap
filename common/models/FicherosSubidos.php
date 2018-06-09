<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ficheros_subidos".
 *
 * @property int $id
 * @property resource $contenido
 */
class FicherosSubidos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ficheros_subidos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contenido'], 'required'],
            [['contenido'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contenido' => 'Contenido',
        ];
    }
}
