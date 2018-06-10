<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;

use yii\helpers\Url;
use yii\helpers\Html;

use common\models\Roles;
use common\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\SolicitudesLucha;

use backend\models\ElegirUsuarioForm;
use common\models\ZonasHorarias;
use common\models\Nacionalidades;

use common\components\UrlConvert;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'aceptar-solicitud' => ['POST'],
                    'cancelar-solicitud' => ['POST'],
                    'aceptar-solicitud-lucha' => ['POST'],
                    'borrar-solicitud-lucha' => ['POST'],
                    'correo-verificar' => ['POST'],
                    'correo-solicitud-lucha' => ['POST'],
                    'eliminar' => ['POST'],
                    'expulsar' => ['POST'],
                    'quitarExpulsion' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'invitar',
                    'aceptar-solicitud',
                    'cancelar-solicitud',
                    'aceptar-solicitud-lucha',
                    'borrar-solicitud-lucha',
                    'eliminar',
                    'expulsar',
                    'quitarExpulsion',
                    'correo-verificar',
                    'correo-solicitud-lucha',
                    'invitar',
                    'accion-usuario'
                ],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['loginBackEnd']
                    ],
                    [
                        'actions' => [
                            'invitar',
                            'aceptar-solicitud',
                            'aceptar-solicitud-lucha',
                            'cancelar-solicitud',
                            'borrar-solicitud-lucha',
                            'correo-verificar',
                            'correo-solicitud-lucha',
                        ],
                        'allow' => true,
                        'roles' => ['solicitudes']
                    ],
                    [
                        'actions' => ['eliminar', 'expulsar', 'quitarExpulsion', 'accion-usuario'],
                        'allow' => true,
                        'roles' => ['usuarios']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Usuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarios = Usuarios::findLoginExpulsadosQuery()
                            ->joinWith('roles')
                            ->orderBy('roles.id')
                            ->andWhere('usuarios.id != ' . \Yii::$app->user->identity->id)
                            ->andWhere('usuarios.id != 1') // Administrador
                            ->all();

        $usuariosPendientes = Usuarios::pendientes();

        $solicitudesLucha = SolicitudesLucha::find()
                                            ->orderBy('aceptada ASC')
                                            ->all();

        //var_dump($solicitudesLucha); die();

        return $this->render('index', [
            'usuarios' => $usuarios,
            'usuariosPendientes' => $usuariosPendientes,
            'solicitudesLucha' => $solicitudesLucha
        ]);
    }

    /**
     * Acepta la solicitud de un usuario que ha pedido invitación al equipo.
     * @return mixed
     */
    public function actionAceptarSolicitud()
    {
        $id = Yii::$app->request->post('usuario');

        $model = $this->findModel($id);

        $model->scenario = Usuarios::ESCENARIO_ACEPTAR_SOLICITUD;
        $model->activo = true;

        if ($model->save() && $this->enviarCorreoConfirmacion($model)) {
            \Yii::$app->session->setFlash('success', 'Se ha enviado el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b> correctamente');
        } else {
            \Yii::$app->session->setFlash('danger', 'No se ha podido enviar el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b>, ha ocurrido un error inesperado.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Acepta la solicitud de un usuario que ha pedido invitación al equipo.
     * @return mixed
     */
    public function actionAceptarSolicitudLucha()
    {
        $id = Yii::$app->request->post('solicitud');
        $model = SolicitudesLucha::findOne($id);

        if ($model) {
            $model->aceptada = true;

            $model->validate();

            if ($model->save() && $this->enviarCorreoSolicitudLucha($model)) {
                \Yii::$app->session->setFlash('success', 'Se ha enviado el correo de aceptación de lucha a la dirección <b>' . Html::encode($model->correo) . '</b> correctamente');
            } else {
                \Yii::$app->session->setFlash('danger', 'No se ha podido enviar el correo de aceptación de lucha a la dirección <b>' . Html::encode($model->correo) . '</b>, ha ocurrido un error inesperado.');
            }
        }

        return $this->redirect(['index']);
    }

    private function usuarioPost()
    {
        $id = Yii::$app->request->post('usuario');
        return $this->findModel($id);
    }

    /**
     * Cancela la solicitud de un usuario que ha pedido una invitación al equipo.
     * @return mixed
     */
    public function actionCancelarSolicitud()
    {
        $this->usuarioPost()->delete();
        return $this->redirect(['index']);
    }

    /**
     * Borra la solicitud de un clan que ha pedido una lucha contra el equipo.
     * @return mixed
     */
    public function actionBorrarSolicitudLucha()
    {
        $id = Yii::$app->request->post('solicitud');

        $solicitud = SolicitudesLucha::findOne($id);

        if ($solicitud) {
            $solicitud->delete();
        }

        return $this->redirect(['index']);
    }

    public function actionEliminar()
    {
        $this->usuarioPost()->delete();
        return $this->redirect(['index']);
    }

    public function actionExpulsar()
    {
        $this->usuarioPost()->expulsar();
        return $this->redirect(['index']);
    }

    public function actionQuitarExpulsion()
    {
        $this->usuarioPost()->quitarExpulsion();
        return $this->redirect(['index']);
    }

    /**
     * Envia un correo de confirmación a un usuario en concreto
     * @param  Usuarios $usuario Usuario al que se le va a enviar el correo
     */
    private function enviarCorreoConfirmacion($usuario)
    {
        $enlace = UrlConvert::toFrontEnd(Url::to(['/site/verificar', 'auth' => $usuario->verificado], true));

        return Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($usuario->correo)
                        ->setSubject('Correo de confirmacion de Skeleton\'s Trap')
                        ->setHtmlBody('Hola, para poder utilizar la cuenta debes verificar la cuenta a través del siguiente enlace <a href="' . $enlace . '">' . $enlace . '</a>')
                        ->setTextBody('Hola, para poder utilizar la cuenta debes verificar la cuenta a través del siguiente enlace: ' . $enlace)
                        ->send();
    }

    /**
     * Envia un correo de aceptación de lucha contra el equipo
     * @param SolicitudesLucha $solicitud Solicitud al que se le va a enviar el correo
     */
    private function enviarCorreoSolicitudLucha($solicitud)
    {
        return Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($solicitud->correo)
                        ->setSubject('Correo de aceptación de la solicitud de lucha contra Skeleton\'s Trap')
                        ->setHtmlBody('Hola, si has recibido este correo significa que hemos aceptado luchar contra tu clan con TAG <b>' . $solicitud->tag . '</b>. Nos pondremos en contacto en la mayor brevedad posible.')
                        ->setTextBody('Hola, si has recibido este correo significa que hemos aceptado luchar contra tu clan con TAG ' . $solicitud->tag . '. Nos pondremos en contacto en la mayor brevedad posible.')
                        ->send();
    }

    /**
     * Vuelve a enviar un correo de verificación de un usuario
     * @return mixed
     */
    public function actionCorreoVerificar()
    {
        $id = Yii::$app->request->post('usuario');
        $model = $this->findModel($id);

        if ($this->enviarCorreoConfirmacion($model)) {
            \Yii::$app->session->setFlash('success', 'Se ha vuelto a enviar el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b> correctamente');
        } else {
            \Yii::$app->session->setFlash('danger', 'No se ha podido enviar el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b>, ha ocurrido un error inesperado.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Vuelve a enviar el correo de aceptación de lucha contra el equipo de un clan que lo solicitó
     * @return mixed
     */
    public function actionCorreoSolicitudLucha()
    {
        $id = Yii::$app->request->post('solicitud');
        $solicitud = SolicitudesLucha::findOne($id);

        if ($solicitud) {
            if ($this->enviarCorreoSolicitudLucha($solicitud)) {
                \Yii::$app->session->setFlash('success', 'Se ha vuelto a enviar el correo de aceptación de lucha a la dirección <b>' . Html::encode($solicitud->correo) . '</b> correctamente');
            } else {
                \Yii::$app->session->setFlash('danger', 'No se ha podido enviar el correo de aceptación de lucha a la dirección <b>' . Html::encode($solicitud->correo) . '</b>, ha ocurrido un error inesperado.');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Invita a un nuevo usuario al equipo.
     * @return mixed
     */
    public function actionInvitar()
    {
        $model = new Usuarios([
            'scenario' => Usuarios::ESCENARIO_INVITAR
        ]);

        $nacionalidadesDatos = Nacionalidades::find()
                                             ->orderBy('pais ASC')
                                             ->asArray()
                                             ->all();

        $zonasHorariasDatos = ZonasHorarias::find()
                                           ->orderBy('zona ASC')
                                           ->asArray()
                                           ->all();

        $nacionalidades = [];
        $zonasHorarias  = [];

        foreach ($nacionalidadesDatos as $key => $value) {
            $idNacionalidad   = $value['id'];
            $paisNacionalidad = $value['pais'];

            $nacionalidades[$idNacionalidad] = $paisNacionalidad;
        }

        foreach ($zonasHorariasDatos as $key => $value) {
            $idZonaHoraria    = $value['id'];

            $zonaZonaHoraria  = $value['zona'];
            $lugarZonaHoraria = $value['lugar'];

            $zonasHorarias[$idZonaHoraria] = 'GMT ' . ($zonaZonaHoraria >= 0 ? '+' : '') . $zonaZonaHoraria . ' - ' . $lugarZonaHoraria;
        }

        //var_dump($model); die();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($this->enviarCorreoConfirmacion($model)) {
                \Yii::$app->session->setFlash('success', 'Se ha enviado el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b> correctamente');
            } else {
                \Yii::$app->session->setFlash('danger', 'No se ha podido enviar el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b>, ha ocurrido un error inesperado.');
            }

            return $this->redirect(['index']);
        }

        return $this->render('invitar', [
            'model' => $model,
            'nacionalidades' => $nacionalidades,
            'zonasHorarias' => $zonasHorarias
        ]);
    }

    /**
     * Lista de usuarios que pueden loguearse y que no sea el propio usuario logueado.
     * @return array Devuelve la lista de usuarios.
     */
    private function listaUsuarios()
    {
        $usuariosDatos = Usuarios::findLoginExpulsadosQuery()
                                 ->with('roles')
                                 ->orderBy('nombre ASC')
                                 ->where('id != ' . \Yii::$app->user->identity->id)
                                 ->andWhere('id != 1')
                                 ->asArray()
                                 ->all();

        $usuarios = [];

        foreach ($usuariosDatos as $key => $value) {
            $idUsuario     = $value['id'];
            $nombreUsuario = $value['nombre'];

            $usuarios[$idUsuario] = $nombreUsuario;
        }

        return $usuarios;
    }

    private function listaRoles()
    {
        $rolesDatos = Roles::find()
                           ->orderBy('id')
                           ->all();

        $roles = [];

        foreach ($rolesDatos as $key => $value) {
            $idRoles     = $value['id'];
            $nombreRoles = $value['nombre'];

            $roles[$idRoles] = $nombreRoles;
        }

        return $roles;
    }

    /**
     * Elimina a un usuario por su id en POST
     */
    public function actionAccionUsuario()
    {
        $model = new ElegirUsuarioForm();

        if (Yii::$app->request->post()) {
            $accionPost = isset(Yii::$app->request->post('ElegirUsuarioForm')['accion']) ? Yii::$app->request->post('ElegirUsuarioForm')['accion'] : '';

            if ($accionPost == 'cambiar-rol') {
                $model = new ElegirUsuarioForm([
                    'scenario' => ElegirUsuarioForm::ESCENARIO_ROL
                ]);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $accion = $model->accion;

            if ($model->validate()) {
                $strUsuarios = implode(', ', array_map(function ($elemento) {
                    return ('\'' . $elemento . '\'');
                }, $model->usuarios_id));

                $usuarios = Usuarios::findLoginExpulsadosQuery()
                                    ->where('id in (' . $strUsuarios . ')')
                                    ->all();

                foreach ($usuarios as $usuario) {
                    if ($accion == 'eliminar') {
                        $usuario->delete();
                    } elseif ($accion == 'expulsar') {
                        $usuario->expulsar();
                    } elseif ($accion == 'quitar-expulsion') {
                        $usuario->quitarExpulsion();
                    } elseif ($accion == 'cambiar-rol') {
                        if ($usuario->id != \Yii::$app->user->identity->id) {
                            $rol = Roles::findOne($model->rol_cambiar);

                            if ($rol) {
                                $usuario->cambiarRol($rol->id);
                            }
                        }
                    }
                }

                \Yii::$app->session->setFlash('success', 'Se ha realizado la acción a los usuarios seleccionados correctamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('accion', [
            'model' => $model,
            'usuarios' => $this->listaUsuarios(),
            'roles' => $this->listaRoles()
        ]);
    }

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La petición a la pagina solicitada no existe.');
    }
}
