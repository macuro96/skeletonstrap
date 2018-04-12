<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permisos".
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 *
 * @property RolesPermisos[] $rolesPermisos
 * @property Roles[] $rols
 */
class Permisos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permisos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 32],
            [['descripcion'], 'string', 'max' => 255],
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
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolesPermisos()
    {
        return $this->hasMany(RolesPermisos::className(), ['permiso_id' => 'id'])->inverseOf('permiso');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRols()
    {
        return $this->hasMany(Roles::className(), ['id' => 'rol_id'])->viaTable('roles_permisos', ['permiso_id' => 'id']);
    }
}
