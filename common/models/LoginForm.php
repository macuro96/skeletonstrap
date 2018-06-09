<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Formulario de logueo
 */
class LoginForm extends Model
{
    /**
     * Usuario
     * @var string
     */
    public $usuario;

    /**
     * Password
     * @var string
     */
    public $password;

    /**
     * Recordar sesión
     * @var bool
     */
    public $rememberMe = true;

    /**
     * Modelo de usuario temporal
     * @var Usuarios
     */
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
            ['password', 'validatePassword', 'skipOnError' => true],
            ['usuario', 'validateVerificado', 'skipOnError' => true],
            ['usuario', 'validateActivo', 'skipOnError' => true],
            ['usuario', 'validateNoExpulsado', 'skipOnError' => true]
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

    /**
     * Validar si el usuario está activado o no
     * @param  mixed $attribute Atributo
     * @param  mixed $params    Parametros
     * @param  mixed $validator Validador
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
     * Validar si el usuario está verificado a través del correo o no.
     * @param  mixed $attribute Atributo
     * @param  mixed $params    Parametros
     * @param  mixed $validator Validador
     */
    public function validateVerificado($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if (!$this->getUser()->estaVerificado) {
                $this->addError($attribute, 'El usuario no está verificado, por favor, compruebe el mensaje que le hemos enviado en su correo electronico para confirmar su cuenta');
            }
        }
    }

    public function validateNoExpulsado($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if ($this->getUser()->estaExpulsado) {
                $this->addError($attribute, 'El usuario está expulsado en este momento, contacte con el soporte para más información');
            }
        }
    }
}
