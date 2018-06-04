<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;

use yii\helpers\Url;
use yii\helpers\Html;

use common\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                    'correo-verificar' => ['POST'],
                    'aceptar-solicitud' => ['POST'],
                    'cancelar-solicitud' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'invitar', 'aceptar-solicitud', 'cancelar-solicitud'],
                'rules' => [
                    [
                        'actions' => ['invitar', 'aceptar-solicitud', 'cancelar-solicitud'],
                        'allow' => true,
                        'roles' => ['administrador']
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@']
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
        $usuarios = Usuarios::find()->all();
        $usuariosPendientes = Usuarios::pendientes();

        return $this->render('index', [
            'usuarios' => $usuarios,
            'usuariosPendientes' => $usuariosPendientes
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

        $model->activo = true;

        if ($model->save() && $this->enviarCorreoConfirmacion($model)) {
            \Yii::$app->session->setFlash('success', 'Se ha enviado el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b> correctamente');
        } else {
            \Yii::$app->session->setFlash('danger', 'No se ha podido enviar el correo de confirmación a la dirección <b>' . Html::encode($model->correo) . '</b>, ha ocurrido un error inesperado.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Cancela la solicitud de un usuario que ha pedido una invitación al equipo.
     * @return mixed
     */
    public function actionCancelarSolicitud()
    {
        $id = Yii::$app->request->post('usuario');
        $this->findModel($id)->delete();

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
     * Verifica un correo de un usuario
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
