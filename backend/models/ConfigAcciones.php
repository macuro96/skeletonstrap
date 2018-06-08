<?php

namespace backend\models;

/**
 * This is the model class for table "config_acciones".
 *
 * @property int $id
 * @property string $accion
 * @property string $mensaje_unete_twitter
 * @property string $mensaje_unete_whatsapp
 */
class ConfigAcciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config_acciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accion'], 'string', 'max' => 1],
            [['mensaje_unete_twitter'], 'string', 'max' => 120],
            [['mensaje_unete_whatsapp'], 'string', 'max' => 255],
            [['accion'], function ($attribute, $params, $validator) {
                if ($this->$attribute != 'd' && $this->$attribute != 'p') {
                    $this->addError($attribute, 'Acción no válida.');
                }
            }, 'skipOnError' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'accion' => 'Accion',
            'mensaje_unete_twitter' => 'Mensaje Unete Twitter',
            'mensaje_unete_whatsapp' => 'Mensaje Unete Whatsapp',
        ];
    }
}
