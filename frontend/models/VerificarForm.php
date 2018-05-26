<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Login form
 */
class VerificarForm extends Model
{
    /**
     * Primer password
     * @var string
     */
    public $password;

    /**
     * Segundo campo de password para la verificación
     * @var string
     */
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Contraseña',
            'password_repeat' => 'Repetir Contraseña',
        ];
    }
}
