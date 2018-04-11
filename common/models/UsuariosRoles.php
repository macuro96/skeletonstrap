<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usuarios_roles".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $rol_id
 *
 * @property Usuarios $usuario
 * @property Usuarios $rol
 */
class UsuariosRoles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuarios_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario_id', 'rol_id'], 'required'],
            [['usuario_id', 'rol_id'], 'default', 'value' => null],
            [['usuario_id', 'rol_id'], 'integer'],
            [['usuario_id', 'rol_id'], 'unique', 'targetAttribute' => ['usuario_id', 'rol_id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['rol_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['rol_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'rol_id' => 'Rol ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('usuariosRoles');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Roles::className(), ['id' => 'rol_id']);
    }
}
