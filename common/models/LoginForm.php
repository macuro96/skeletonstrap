<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $usuario;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // usuario and password are both required
            [['usuario', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['usuario', 'validateVerificado', 'skipOnError' => true],
            ['usuario', 'validateActivo', 'skipOnError' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario' => 'Usuario',
            'password' => 'Contraseña',
            'rememberMe' => 'Recuérdame'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuario y/o password incorrecto.');
            }
        }
    }

    /**
     * Logs in a user using the provided usuario and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[usuario]]
     *
     * @return Usuarios|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Usuarios::findByNombre($this->usuario);
        }

        return $this->_user;
    }

    public function validateActivo($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->estaActivo) {
                $this->addError($attribute, 'El usuario esta desactivado temporalmente');
            }
        }
    }

    public function validateVerificado($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->estaVerificado) {
                $this->addError($attribute, 'El usuario no está verificado, por favor, compruebe el mensaje que le hemos enviado en su correo electronico para confirmar su cuenta');
            }
        }
    }
}
