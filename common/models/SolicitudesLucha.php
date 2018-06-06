<?php

namespace common\models;

/**
 * This is the model class for table "solicitudes_lucha".
 *
 * @property int $id
 * @property string $tag
 */
class SolicitudesLucha extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'solicitudes_lucha';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'nacionalidad_id', 'correo'], 'required'],
            [['nacionalidad_id', 'clan_id'], 'default', 'value' => null],
            [['nacionalidad_id', 'clan_id'], 'integer'],
            [['aceptada'], 'boolean'],
            [['tag'], 'string', 'max' => 16],
            [['correo'], 'email'],
            [['correo'], 'unique'],
            [['tag'], 'unique', 'message' => 'Ya ha sido solicitado con anterioridad un clan con ese TAG'],
            [['tag'], function ($attribute, $params, $validator) {
                if (!$this->hasErrors()) {
                    $solicitudLucha  = null;
                    $clanBuscado = Clanes::find()->where(['tag' => $this->$attribute])->one();

                    if ($clanBuscado != null) {
                        $solicitudLucha = self::find()
                                             ->where(['clan_id' => $clanBuscado->id])
                                             ->one();
                    }

                    if ($solicitudLucha == null) {
                        if ($clanBuscado == null) {
                            $clanBuscado = Clanes::findAPI('clan', [
                                'tag' => [
                                    $this->tag
                                ]
                            ]);

                            $clanBuscado = (!empty($clanBuscado) ? $clanBuscado[0] : $clanBuscado);
                        }

                        if ($clanBuscado == null) {
                            $this->addError($attribute, 'No existe un clan con ese TAG');
                        } else {
                            $this->clan_id = $clanBuscado->id;
                        }
                    } else {
                        $this->addError($attribute, 'Ya existe un clan con ese TAG asociado');
                    }
                }
            }, 'skipOnEmpty' => true],
            [['clan_id'], 'required'],
            [['nacionalidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nacionalidades::className(), 'targetAttribute' => ['nacionalidad_id' => 'id']],
            [['clan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clanes::className(), 'targetAttribute' => ['clan_id' => 'id']],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag' => 'TAG',
            'nacionalidad_id' => 'Nacionalidad',
            'correo' => 'Correo',
            'clan_id' => 'Clan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClan()
    {
        return $this->hasOne(Clanes::className(), ['id' => 'clan_id'])->inverseOf('solicitudesLuchas');
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNacionalidad()
    {
        return $this->hasOne(Nacionalidades::className(), ['id' => 'nacionalidad_id'])->inverseOf('solicitudesLuchas');
    }
}
