<?php
namespace backend\models;

use Yii;

/**
 * Login form
 */
class LoginForm extends \common\models\LoginForm
{
    /**
     * Usuario administrador temporal.
     * @var string
     */
    private $_userAdmin;

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
                $this->addError($attribute, 'El usuario no es un administrador o usuario y/o password incorrecto.');
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
        if ($this->_userAdmin === null) {
            $this->_userAdmin = Usuarios::findByNombre($this->usuario);
        }

        return $this->_userAdmin;
    }

    /**
     * {@inheritdoc}
     */
    public function validateActivo($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if (!$this->getUser()->estaActivo) {
                $this->addError($attribute, 'El usuario esta desactivado temporalmente');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateVerificado($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if (!$this->getUser()->estaVerificado) {
                $this->addError($attribute, 'El usuario no está verificado, por favor, compruebe el mensaje que le hemos enviado en su correo electronico para confirmar su cuenta');
            }
        }
    }
}
