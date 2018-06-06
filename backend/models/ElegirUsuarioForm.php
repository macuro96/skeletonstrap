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
    public $usuarios_id;

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
            [['accion'], 'required'],
            [['usuarios_id'], function ($attribute, $params, $validator) {
                if (!$this->hasErrors()) {
                    if ($this->accion != 'eliminar' && $this->accion != 'expulsar' && $this->accion != 'quitar-expulsion') {
                        $this->addError($attribute, 'La acción debe ser eliminar, expulsar o quitar expulsión.');
                    }
                    if ($this->accion == 'expulsar' || $this->accion == 'quitar-expulsion') {
                        $usuarios = [];

                        foreach ($this->usuarios_id as $usuario_id) {
                            $usuarioTemp = Usuarios::findOne($usuario_id);

                            if ($usuarioTemp) {
                                $usuarios[] = $usuarioTemp;
                            }
                        }

                        if (count($usuarios) != count($this->usuarios_id)) {
                            $this->addError('accion', 'Hay algun usuario elegido que no existe.');
                        } else {
                            if ($this->accion == 'expulsar') {
                                $cont = 0;

                                foreach ($usuarios as $usuario) {
                                    if ($usuario->estaExpulsado) {
                                        $this->addError($cont++ . '-expulsado', 'El usuario ya ha sido expulsado.');
                                    }
                                }
                            } elseif ($this->accion == 'quitar-expulsion') {
                                $cont = 0;

                                foreach ($usuarios as $usuario) {
                                    if (!$usuario->estaExpulsado) {
                                        $this->addError($cont++ . '-noexpulsado', 'El usuario no está expulsado.');
                                    }
                                }
                            }
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
            'usuarios_id' => 'Usuario',
            'accion'     => 'Acción',
        ];
    }
}
