<?php

namespace common\models;

/**
 * This is the model class for table "directo".
 *
 * @property int $id
 * @property string $titulo
 * @property string $subtitulo
 * @property string $mensaje_twitter
 * @property string $mensaje_whatsapp
 * @property string $marcador_propio
 * @property string $marcador_oponente
 * @property string $oponente_tag
 * @property int    $clan_id
 * @property resource $logo
 */
class Directo extends \yii\db\ActiveRecord
{
    public $file;

    const ESCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'directo';
    }

    public function scenarios()
    {
        $escenarios = parent::scenarios();
        $escenarios[self::ESCENARIO_UPDATE] = ['file'];

        return $escenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titulo', 'mensaje_twitter', 'mensaje_whatsapp', 'oponente_tag'], 'required'],
            [['file'], 'required', 'on' => 'default'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png'],
            [['logo'], 'string'],
            [['mensaje_twitter'], 'string', 'max' => 280],
            [['marcador_propio', 'marcador_oponente'], 'number', 'min' => 0, 'max' => 3],
            [['clan_id'], 'default', 'value' => null],
            [['clan_id'], 'integer'],
            [['titulo'], 'string', 'max' => 24],
            [['subtitulo'], 'string', 'max' => 64],
            [['mensaje_whatsapp'], 'string', 'max' => 120],
            [['oponente_tag'], 'string', 'max' => 16],
            [['oponente_tag'], function ($attribute, $params, $validator) {
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
                                    $this->oponente_tag
                                ]
                            ]);

                            $clanBuscado = (!empty($clanBuscado) ? $clanBuscado[0] : $clanBuscado);
                        }

                        if ($clanBuscado == null) {
                            $this->addError($attribute, 'No existe un clan con ese TAG');
                        } else {
                            $this->clan_id = $clanBuscado->id;
                        }
                    }
                }
            }, 'skipOnEmpty' => true],
            [['clan_id'], 'required'],
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
            'titulo' => 'Título',
            'subtitulo' => 'Subtítulo',
            'mensaje_twitter' => 'Mensaje de compartir en twitter',
            'mensaje_whatsapp' => 'Mensaje de compartir en whatsapp',
            'marcador_propio' => 'Marcador de Skeleton\'s Trap',
            'marcador_oponente' => 'Marcador del oponente',
            'oponente_tag' => 'Equipo oponente',
            'clan_id' => 'Clan ID',
            'logo' => 'Logo'
        ];
    }

    public function getLogoSrc()
    {
        return '/images/image.php?t=directo&c=logo';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClan()
    {
        return $this->hasOne(Clanes::className(), ['id' => 'clan_id'])->inverseOf('directos');
    }
}
