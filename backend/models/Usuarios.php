<?php

namespace backend\models;

use yii\db\ActiveQuery;

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
class Usuarios extends \common\models\Usuarios
{
    /**
     * Query de usuarios administradores válidos para el logueo.
     * @return ActiveQuery ActiveQuery
     */
    public static function findAdminQuery()
    {
        $query = static::find()
                     ->join('LEFT JOIN', 'usuarios_roles', 'usuarios.id = usuarios_roles.id')
                     ->join('LEFT JOIN', 'roles', 'usuarios_roles.id = roles.id')
                     ->join('LEFT JOIN', 'roles_permisos', 'roles.id = roles_permisos.rol_id')
                     ->join('LEFT JOIN', 'permisos', 'permisos.id = roles_permisos.permiso_id')
                     ->where(['permisos.nombre' => 'loginBackEnd']);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public static function findLoginQuery()
    {
        return static::findAdminQuery()
                     ->andWhere(['activo' => true])
                     ->andWhere('verificado is null');
    }

    public static function findByNombre(string $nombre)
    {
        return static::findAdminQuery()
                     ->andWhere(['usuarios.nombre' => $nombre])
                     ->one();
    }

    /**
      * Finds an identity by the given ID.
      *
      * @param string|int $id the ID to be looked for
      * @return IdentityInterface|null the identity object that matches the given ID.
      */
    public static function findIdentity($id)
    {
        return static::findAdminQuery()
                     ->andWhere(['usuarios.id' => $id])
                     ->one();
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
        return static::findAdminQuery()
                     ->andWhere(['access_token' => $token])
                     ->one();
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return parent::validateAuthKey($authKey);
    }
}
