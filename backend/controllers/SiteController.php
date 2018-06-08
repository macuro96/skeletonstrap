<?php
namespace backend\controllers;

use Yii;
use Detection\MobileDetect;

use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Config;
use backend\models\LoginForm;
use backend\models\ConfigAcciones;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'index', 'logout'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if ($action->id == 'login') {
                        return $this->goHome();
                    } else {
                        return $this->redirect(['login']);
                    }
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function getConfigAcciones()
    {
        return ConfigAcciones::find()->one();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $config = Config::find()->one();

        $detect = new MobileDetect();

        $msgUnete['whatsapp'] = $config->mensaje_whatsapp;
        $msgUnete['twitter']  = $config->mensaje_twitter;

        return $this->render('index', [
            'configuracionAcciones' => [
                'accion' => $this->configAcciones->accion
            ],
            'detect' => $detect,
            'msgUnete' => $msgUnete
        ]);
    }

    public function actionAccion($activar)
    {
        $config = $this->configAcciones;
        $config->accion = ($activar == 'n' ? null : $activar);

        if (!$config->validate()) {
            throw new BadRequestHttpException('Acción de configuración inválida.');
        }

        $config->save();

        return $this->redirect(['index']);
    }

    public function actionAdministrarCuentas()
    {
        $config = Config::find()->one();

        $model = ($config ?: new Config());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', 'Configuración cambiada correctamente.');

            return $this->redirect(['index']);
        }

        return $this->render('administrar-cuentas.php', [
            'model' => $model
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
