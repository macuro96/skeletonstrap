<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ligas".
 *
 * @property int $id
 * @property string $nombre
 * @property string $icono
 *
 * @property Jugadores[] $jugadores
 */
class Ligas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ligas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'icono'], 'required'],
            [['icono'], 'number'],
            [['nombre'], 'string', 'max' => 255],
            [['icono'], 'unique'],
            [['nombre'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'icono' => 'Icono',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJugadores()
    {
        return $this->hasMany(Jugadores::className(), ['liga_id' => 'id'])->inverseOf('liga');
    }
}
