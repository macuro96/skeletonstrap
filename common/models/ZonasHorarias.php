<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zonas_horarias".
 *
 * @property int $id
 * @property string $zona
 * @property string $lugar
 *
 * @property Usuarios[] $usuarios
 */
class ZonasHorarias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zonas_horarias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zona'], 'required'],
            [['zona'], 'number'],
            [['lugar'], 'string'],
            [['zona'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zona' => 'Zona',
            'lugar' => 'Lugar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['zona_horaria_id' => 'id'])->inverseOf('zonaHoraria');
    }
}
