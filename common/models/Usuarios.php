<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $password
 * @property string $correo
 * @property int $nacionalidad_id
 * @property bool $verificado
 *
 * @property Jugadores $jugadores
 * @property Nacionalidades $nacionalidad
 */
class Usuarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'password', 'correo', 'nacionalidad_id'], 'required'],
            [['nacionalidad_id'], 'default', 'value' => null],
            [['nacionalidad_id'], 'integer'],
            [['verificado'], 'boolean'],
            [['nombre', 'password', 'correo'], 'string', 'max' => 255],
            [['correo'], 'unique'],
            [['nacionalidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nacionalidades::className(), 'targetAttribute' => ['nacionalidad_id' => 'id']],
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
            'password' => 'Password',
            'correo' => 'Correo',
            'nacionalidad_id' => 'Nacionalidad ID',
            'verificado' => 'Verificado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJugadores()
    {
        return $this->hasOne(Jugadores::className(), ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNacionalidad()
    {
        return $this->hasOne(Nacionalidades::className(), ['id' => 'nacionalidad_id'])->inverseOf('usuarios');
    }
}
