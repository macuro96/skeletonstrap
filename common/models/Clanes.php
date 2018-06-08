<?php

namespace common\models;

/**
 * This is the model class for table "clanes".
 *
 * @property int $id
 * @property string $nombre
 * @property string $tag
 * @property string $descripcion
 * @property string $copas
 * @property string $copas_requeridas
 * @property int $donaciones_semana
 *
 */
class Clanes extends \common\models\ClashRoyaleCache
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clanes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'copas', 'copas_requeridas', 'tag', 'numero_miembros'], 'required'],
            [['donaciones_semana'], 'default', 'value' => null],
            [['donaciones_semana', 'numero_miembros'], 'integer'],
            [['descripcion'], 'string'],
            [['copas', 'copas_requeridas', 'numero_miembros'], 'number'],
            [['nombre'], 'string', 'max' => 255],
            [['tag'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return static::attributeLabelsStatic();
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabelsStatic()
    {
        return array_combine(self::clavesLabelsStatic(), [
            'ID',
            'Nombre',
            'Descripción',
            'TAG',
            'Copas',
            'Mínimo de copas',
            'Donaciones por semana',
            'Número de miembros'
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function clavesLabelsStatic()
    {
        return [
            'id',
            'nombre',
            'descripcion',
            'tag',
            'copas',
            'copas_requeridas',
            'donaciones_semana',
            'numero_miembros'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function clavesCache()
    {
        return array_combine([
            'id',
            'name',
            'description',
            'tag',
            'score',
            'requiredScore',
            'donations',
            'memberCount',
        ], self::clavesLabelsStatic());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudesLuchas()
    {
        return $this->hasMany(SolicitudesLucha::className(), ['clan_id' => 'id'])->inverseOf('clan');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectos()
    {
        return $this->hasMany(Directo::className(), ['clan_id' => 'id'])->inverseOf('clan');
    }
}
