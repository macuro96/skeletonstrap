<?php

namespace common\models;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $mensaje_twitter
 * @property string $mensaje_unete_twitter
 * @property string $mensaje_whatsapp
 * @property string $mensaje_unete_whatsapp
 * @property string $usuario_twitch
 * @property string $password_twitter
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accion'], 'string', 'max' => 1],
            [['coleccion_twitch'], 'string', 'max' => 64],
            [['mensaje_twitter', 'mensaje_unete_twitter', 'mensaje_whatsapp', 'mensaje_unete_whatsapp', 'usuario_twitch'], 'required'],
            [['mensaje_twitter', 'mensaje_unete_twitter'], 'string', 'max' => 120],
            [['mensaje_whatsapp', 'mensaje_unete_whatsapp', 'usuario_twitch'], 'string', 'max' => 255],
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
            'mensaje_twitter' => 'Mensaje Twitter',
            'mensaje_unete_twitter' => 'Mensaje Unete Twitter',
            'mensaje_whatsapp' => 'Mensaje Whatsapp',
            'mensaje_unete_whatsapp' => 'Mensaje Unete Whatsapp',
            'usuario_twitch' => 'Usuario Twitch',
            'coleccion_twitch' => 'Código de colección de videos',
            'accion' => 'Acción',
        ];
    }
}
