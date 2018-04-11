<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "nacionalidades".
 *
 * @property int $id
 * @property string $nombre
 * @property string $abreviatura
 * @property string $tramo_horario
 *
 * @property ConfigEquipo[] $configEquipos
 * @property Usuarios[] $usuarios
 */
class Nacionalidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nacionalidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'abreviatura', 'tramo_horario'], 'required'],
            [['tramo_horario'], 'number', 'min' => -12, 'max' => 12],
            [['nombre'], 'string', 'max' => 32],
            [['abreviatura'], 'string', 'max' => 3],
            [['abreviatura'], 'unique'],
            [['nombre'], 'unique'],
            [['tramo_horario'], 'unique'],
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
            'abreviatura' => 'Abreviatura',
            'tramo_horario' => 'Tramo Horario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfigEquipos()
    {
        return $this->hasMany(ConfigEquipo::className(), ['nacionalidad_id' => 'id'])->inverseOf('nacionalidad');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['nacionalidad_id' => 'id'])->inverseOf('nacionalidad');
    }
}
