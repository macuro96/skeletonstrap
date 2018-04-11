<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property RolesPermisos[] $rolesPermisos
 * @property Permisos[] $permisos
 * @property VisibilidadClanes[] $visibilidadClanes
 * @property Clanes[] $clans
 * @property VisibilidadEventos[] $visibilidadEventos
 * @property Eventos[] $eventos
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 32],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolesPermisos()
    {
        return $this->hasMany(RolesPermisos::className(), ['rol_id' => 'id'])->inverseOf('rol');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermisos()
    {
        return $this->hasMany(Permisos::className(), ['id' => 'permiso_id'])->viaTable('roles_permisos', ['rol_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisibilidadClanes()
    {
        return $this->hasMany(VisibilidadClanes::className(), ['rol_id' => 'id'])->inverseOf('rol');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClans()
    {
        return $this->hasMany(Clanes::className(), ['id' => 'clan_id'])->viaTable('visibilidad_clanes', ['rol_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisibilidadEventos()
    {
        return $this->hasMany(VisibilidadEventos::className(), ['rol_id' => 'id'])->inverseOf('rol');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['id' => 'evento_id'])->viaTable('visibilidad_eventos', ['rol_id' => 'id']);
    }
}
