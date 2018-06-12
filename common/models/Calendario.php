<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "calendario".
 *
 * @property int $id
 * @property int $etiqueta
 * @property string $fecha
 * @property string $hora
 * @property string $descripcion
 * @property resource $imagen
 * @property int $visibilidad
 *
 * @property EventoEtiquetas $etiqueta0
 * @property Roles $visibilidad0
 */
class Calendario extends \yii\db\ActiveRecord
{
    public $realizado;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['realizado'], 'safe'],
            [['etiqueta', 'fecha', 'hora'], 'required'],
            [['etiqueta', 'visibilidad'], 'default', 'value' => null],
            [['etiqueta', 'visibilidad'], 'integer'],
            [['visibilidad'], function ($attribute, $params, $validator) {
                if ($this->visibilidad == 0) {
                    $this->visibilidad = null;
                }
            }],
            [['fecha', 'hora'], 'safe'],
            [['hora'], function ($attribute, $params, $validator) {
                if (explode(':', $this->hora)[0] >= 24) {
                    $this->addError($attribute, 'La hora no puede ser mayor de las 24 h que tiene el día.');
                }
            }],
            [['descripcion', 'imagen'], 'string'],
            [['etiqueta'], 'exist', 'skipOnError' => true, 'targetClass' => EventoEtiquetas::className(), 'targetAttribute' => ['etiqueta' => 'id']],
            [['visibilidad'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['visibilidad' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'etiqueta' => 'Etiqueta',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'descripcion' => 'Descripción',
            'imagen' => 'Imagen',
            'visibilidad' => 'Visibilidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiqueta0()
    {
        return $this->hasOne(EventoEtiquetas::className(), ['id' => 'etiqueta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisibilidad0()
    {
        return $this->hasOne(Roles::className(), ['id' => 'visibilidad']);
    }
}
