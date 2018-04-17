<?php

namespace common\models;

use Yii;
use \yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $password
 * @property string $correo
 * @property int $nacionalidad_id
 * @property bool $verificado
 *
 * @property Jugadores $jugadores
 * @property Nacionalidades $nacionalidad
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $normas;

    const ESCENARIO_INVITAR   = 'invitar';
    const ESCENARIO_VERIFICAR = 'verificar';
    const ESCENARIO_UNETE     = 'unete';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'correo', 'nacionalidad_id'], 'required'],
            [['nombre'], 'string', 'min' => 4],
            [['password'], 'required', 'on' => [self::SCENARIO_DEFAULT, self::ESCENARIO_VERIFICAR]],
            [['password'], 'default', 'value' => \Yii::$app->security->generatePasswordHash(''), 'on' => [self::ESCENARIO_INVITAR, self::ESCENARIO_UNETE]],
            [['nacionalidad_id'], 'default', 'value' => null],
            [['nacionalidad_id'], 'integer'],
            [['activo'], 'boolean'],
            [['nombre', 'password', 'correo', 'access_token', 'auth_key', 'verificado'], 'string', 'max' => 255],
            [['correo', 'nombre'], 'unique'],
            [['nacionalidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nacionalidades::className(), 'targetAttribute' => ['nacionalidad_id' => 'id']],
            [['normas'], 'required', 'on' => self::ESCENARIO_UNETE],
            [['normas'], 'boolean', 'on' => self::ESCENARIO_UNETE],
            [['normas'], 'compare', 'compareValue' => true, 'message' => 'Las normas deben ser aceptadas obligatoriamente']
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['normas']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'password' => 'Contraseña',
            'correo' => 'Correo',
            'nacionalidad_id' => 'Nacionalidad',
            'verificado' => 'Verificado',
            'normas' => 'Acepto las normas',
        ];
    }

    public function getEstaActivo()
    {
        return $this->activo === true;
    }

    public function getEstaVerificado()
    {
        return $this->verificado === null;
    }

    public static function findLoginQuery()
    {
        return static::find()
                     ->where(['activo' => true])
                     ->andWhere('verificado is null');
    }

    public static function pendientes()
    {
        return static::find()
                     ->where(['activo' => false])
                     ->orWhere('verificado is not null')
                     ->all();
    }

    public static function findByNombre($nombre)
    {
        return static::findOne(['nombre' => $nombre]);
    }

    public static function findByVerificado($verificado)
    {
        return static::findOne(['verificado' => $verificado]);
    }

    /**
      * Finds an identity by the given ID.
      *
      * @param string|int $id the ID to be looked for
      * @return IdentityInterface|null the identity object that matches the given ID.
      */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

     /**
      * Finds an identity by the given token.
      *
      * @param string $token the token to be looked for
      * @param string|null $type
      * @return IdentityInterface|null the identity object that matches the given token.
      */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

     /**
      * @return int|string current user ID
      */
    public function getId()
    {
        return $this->id;
    }

     /**
      * @return string current user auth key
      */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

     /**
      * @param string $authKey
      * @return bool if auth key is valid for current user
      */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getEsAdministrador()
    {
        return $this->getRoles()->where(['nombre' => 'administrador'])->one() !== null;
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getJugadores()
    {
        return $this->hasOne(Jugadores::className(), ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNacionalidad()
    {
        return $this->hasOne(Nacionalidades::className(), ['id' => 'nacionalidad_id'])->inverseOf('usuarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuariosRoles()
    {
        return $this->hasMany(UsuariosRoles::className(), ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Roles::className(), ['id' => 'rol_id'])->viaTable('usuarios_roles', ['usuario_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();

                if ($this->scenario == self::ESCENARIO_INVITAR) {
                    $this->activo     = true;
                    $this->verificado = \Yii::$app->security->generateRandomString();
                }

                if ($this->scenario == self::ESCENARIO_UNETE) {
                    $this->verificado = \Yii::$app->security->generateRandomString();
                }
            } else {
                if ($this->scenario == self::ESCENARIO_VERIFICAR) {
                    $this->verificado = null;
                    $this->password   = \Yii::$app->security->generatePasswordHash($this->password);
                }
            }
            return true;
        }
        return false;
    }
}
