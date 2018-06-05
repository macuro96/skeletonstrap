<?php
namespace backend\models;

use Yii;
use yii\base\Model;

use common\models\Usuarios;

/**
 * Formulario de logueo
 */
class ElegirUsuarioForm extends Model
{
    /**
     * Modelo de usuario temporal
     * @var Usuarios
     */
    public $usuario_id;

    /**
     * Accion de eliminar o expulsar un usuario: 'eliminar' o 'expulsar'
     * @var string
     */
    public $accion;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'accion'], 'required'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['accion'], function ($attribute, $params, $validator) {
                if (!$this->hasErrors()) {
                    if ($this->accion != 'eliminar' && $this->accion != 'expulsar' && $this->accion != 'quitar-expulsion') {
                        $this->addError($attribute, 'La acción debe ser eliminar, expulsar o quitar expulsión.');
                    }
                    if ($this->accion == 'expulsar' || $this->accion == 'quitar-expulsion') {
                        $usuario = Usuarios::findOne($this->usuario_id);

                        if ($this->accion == 'expulsar' && $usuario->estaExpulsado) {
                            $this->addError($attribute, 'El usuario ya ha sido expulsado.');
                        } elseif ($this->accion == 'quitar-expulsion' && !$usuario->estaExpulsado) {
                            $this->addError($attribute, 'El usuario no está expulsado.');
                        }
                    }
                }
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Usuario',
            'accion'     => 'Acción',
        ];
    }
}
