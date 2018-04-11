<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "roles_permisos".
 *
 * @property int $id
 * @property int $rol_id
 * @property int $permiso_id
 *
 * @property Permisos $permiso
 * @property Roles $rol
 */
class RolesPermisos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roles_permisos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rol_id', 'permiso_id'], 'required'],
            [['rol_id', 'permiso_id'], 'default', 'value' => null],
            [['rol_id', 'permiso_id'], 'integer'],
            [['rol_id', 'permiso_id'], 'unique', 'targetAttribute' => ['rol_id', 'permiso_id']],
            [['permiso_id'], 'exist', 'skipOnError' => true, 'targetClass' => Permisos::className(), 'targetAttribute' => ['permiso_id' => 'id']],
            [['rol_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['rol_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rol_id' => 'Rol ID',
            'permiso_id' => 'Permiso ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermiso()
    {
        return $this->hasOne(Permisos::className(), ['id' => 'permiso_id'])->inverseOf('rolesPermisos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Roles::className(), ['id' => 'rol_id'])->inverseOf('rolesPermisos');
    }
}
