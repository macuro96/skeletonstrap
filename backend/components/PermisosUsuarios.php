<?php

namespace backend\components;

use \Yii;

use common\models\Roles;
use common\models\Permisos;
use common\models\RolesPermisos;
use common\models\UsuariosRoles;

/**
 * Componente relacionado con los permisos de los usuarios.
 */
class PermisosUsuarios
{
    public static function up()
    {
        $auth = Yii::$app->authManager;

        $aPermisos      = Permisos::find()->all();
        $aRoles         = Roles::find()->all();
        $aUsuariosRoles = UsuariosRoles::find()->all();

        $aPermisosAuth = [];

        /* Permisos */
        for ($p = 0; $p < count($aPermisos); $p++) {
            $oPermiso = $aPermisos[$p];

            $aPermisosAuth[$oPermiso->nombre] = $auth->createPermission($oPermiso->nombre);
            $aPermisosAuth[$oPermiso->nombre]->description = $oPermiso->descripcion;
            $auth->add($aPermisosAuth[$oPermiso->nombre]);
        }
        /* -------- */

        $aRolesAuth = [];

        /* Roles */
        for ($r = 0; $r < count($aRoles); $r++) {
            $nombreRol = $aRoles[$r]->nombre;

            $aRolesAuth[$nombreRol] = $auth->createRole($nombreRol);

            $aRolesPermisos = RolesPermisos::find()->where(['rol_id' => $aRoles[$r]->id])->all();
            $auth->add($aRolesAuth[$nombreRol]);

            for ($rp = 0; $rp < count($aRolesPermisos); $rp++) {
                $auth->addChild($aRolesAuth[$nombreRol], $aPermisosAuth[$aRolesPermisos[$rp]->permiso->nombre]);
            }
        }
        /* ----- */

        /* Asignacion de roles */
        for ($us = 0; $us < count($aUsuariosRoles); $us++) {
            $auth->assign($aRolesAuth[$aUsuariosRoles[$us]->rol->nombre], $aUsuariosRoles[$us]->usuario->id);
        }
        /* ------------------- */
    }

    public static function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
