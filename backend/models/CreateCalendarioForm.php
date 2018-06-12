<?php
namespace backend\models;

use Yii;
use yii\base\Model;

use common\models\Calendario;

/**
 * Formulario de logueo
 */
class CreateCalendarioForm extends Model
{
    public $evento_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evento_id'], 'required'],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Calendario::className(), 'targetAttribute' => ['evento_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'evento_id' => 'Evento'
        ];
    }
}
