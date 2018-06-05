<?php
namespace backend\models;

use Yii;
use yii\base\Model;

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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id'], 'required'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Usuario',
        ];
    }
}
