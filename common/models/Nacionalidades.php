<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "nacionalidades".
 *
 * @property int $id
 * @property string $nombre
 * @property string $pais
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
            [['nombre', 'pais'], 'required'],
            [['nombre', 'pais'], 'string', 'max' => 32],
            [['nombre'], 'unique'],
            [['pais'], 'unique'],
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
            'pais' => 'Pais',
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
