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

    public function actionCancelarSolicitud()
    {
        $id = Yii::$app->request->post('usuario');
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

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

    public function actionInvitar()
    {
        $model = new Usuarios([
            'scenario' => Usuarios::ESCENARIO_INVITAR
        ]);

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
        ]);
    }

    /**
     * Displays a single Usuarios model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    */

    /**
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*
    public function actionCreate()
    {
        $model = new Usuarios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    */

    /**
     * Updates an existing Usuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    */

    /**
     * Deletes an existing Usuarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    */

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
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
