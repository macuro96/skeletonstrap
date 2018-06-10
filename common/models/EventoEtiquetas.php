<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "evento_etiquetas".
 *
 * @property int $id
 * @property string $nombre
 * @property string $color
 *
 * @property Calendario[] $calendarios
 * @property Eventos[] $eventos
 */
class EventoEtiquetas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evento_etiquetas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 32],
            [['color'], 'string', 'max' => 6],
            [['color'], 'unique'],
            [['nombre'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'color' => 'Color',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarios()
    {
        return $this->hasMany(Calendario::className(), ['etiqueta' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['evento_etiqueta_id' => 'id']);
    }
}
