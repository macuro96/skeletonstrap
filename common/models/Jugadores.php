<?php

namespace common\models;

/**
 * This is the model class for table "jugadores".
 *
 * @property int $id
 * @property string $tag
 * @property string $clan_tag
 * @property string $clan_rol
 * @property string $nombre
 * @property string $nivel
 * @property string $copas
 * @property string $max_copas
 * @property int $partidas_totales
 * @property int $victorias
 * @property int $derrotas
 * @property int $empates
 * @property int $victorias_tres_coronas
 * @property int $donaciones_totales
 * @property int $donaciones_equipo
 * @property string $cartas_descubiertas
 * @property int $desafio_max_victorias
 * @property string $desafio_cartas_ganadas
 * @property int $liga_id
 *
 * @property Batallas[] $batallas
 * @property ClanJugadores[] $clanJugadores
 * @property Clanes[] $clans
 * @property CofresCiclos $cofresCiclos
 * @property EventoIntegrantes[] $eventoIntegrantes
 * @property Eventos[] $eventos
 * @property Ligas $liga
 */
class Jugadores extends \common\models\ClashRoyaleCache
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jugadores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'nombre', 'nivel', 'copas', 'max_copas', 'partidas_totales', 'victorias', 'derrotas', 'empates', 'cartas_descubiertas'], 'required'],
            [['nivel', 'copas', 'max_copas', 'cartas_descubiertas', 'desafio_cartas_ganadas'], 'number'],
            [['partidas_totales', 'victorias', 'derrotas', 'empates', 'victorias_tres_coronas', 'donaciones_totales', 'donaciones_equipo', 'desafio_max_victorias', 'liga_id'], 'default', 'value' => null],
            [['partidas_totales', 'victorias', 'derrotas', 'empates', 'victorias_tres_coronas', 'donaciones_totales', 'donaciones_equipo', 'desafio_max_victorias', 'liga_id'], 'integer'],
            [['tag', 'clan_tag', 'clan_rol'], 'string', 'max' => 16],
            [['nombre'], 'string', 'max' => 255],
            [['tag'], 'unique'],
            [['liga_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ligas::className(), 'targetAttribute' => ['liga_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return static::attributeLabelsStatic();
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabelsStatic()
    {
        return array_combine(self::clavesLabelsStatic(), [
            'ID',
            'TAG',
            'Nombre',
            'Nivel',
            'Copas',
            'Máximo de copas',
            'Partidas totales',
            'Victorias',
            'Derrotas',
            'Empates',
            'Victorias tres coronas',
            'Donaciones totales',
            'Donaciones al equipo',
            'Clan',
            'Cartas descubiertas',
            'Máximas victorias en desafio',
            'Cartas ganadas en desafio',
            'Arena',
            'Rol'
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function clavesLabelsStatic()
    {
        return [
            'id',
            'tag',
            'nombre',
            'nivel',
            'copas',
            'max_copas',
            'partidas_totales',
            'victorias',
            'derrotas',
            'empates',
            'victorias_tres_coronas',
            'donaciones_totales',
            'donaciones_equipo',
            'clan_tag',
            'cartas_descubiertas',
            'desafio_max_victorias',
            'desafio_cartas_ganadas',
            'liga_id',
            'clan_rol'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function clavesCache()
    {
        return array_combine([
            'id',
            'tag',
            'name',
            'stats.level',
            'trophies',
            'stats.maxTrophies',
            'games.total',
            'games.wins',
            'games.losses',
            'games.draws',
            'stats.threeCrownWins',
            'stats.totalDonations',
            'clan.donations',
            'clan.tag',
            'stats.cardsFound',
            'stats.challengeMaxWins',
            'stats.challengeCardsWon',
            'arena.arenaID',
            'clan.role'
        ], self::clavesLabelsStatic());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatallas()
    {
        return $this->hasMany(Batallas::className(), ['jugador_id' => 'id'])->inverseOf('jugador');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClanJugadores()
    {
        return $this->hasMany(ClanJugadores::className(), ['jugador_id' => 'id'])->inverseOf('jugador');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClans()
    {
        return $this->hasMany(Clanes::className(), ['id' => 'clan_id'])->viaTable('clan_jugadores', ['jugador_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCofresCiclos()
    {
        return $this->hasOne(CofresCiclos::className(), ['jugador_id' => 'id'])->inverseOf('jugador');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventoIntegrantes()
    {
        return $this->hasMany(EventoIntegrantes::className(), ['jugador_id' => 'id'])->inverseOf('jugador');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['id' => 'evento_id'])->viaTable('evento_integrantes', ['jugador_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLiga()
    {
        return $this->hasOne(Ligas::className(), ['id' => 'liga_id'])->inverseOf('jugadores');
    }

    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'jugador_id'])->inverseOf('jugadores');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $roles = [
                'member' => 'Miembro',
                'coLeader' => 'Colíder',
                'leader' => 'Líder'
            ];

            if (isset($roles[$this->clan_rol])) {
                $this->clan_rol = $roles[$this->clan_rol];
            }

            return true;
        }
        return false;
    }
}
