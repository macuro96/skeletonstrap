<?php
namespace backend\controllers;

use Yii;
use Detection\MobileDetect;

use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Config;
use common\models\Directo;
use common\components\ImageFile;

use backend\models\LoginForm;

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
                'only' => ['login', 'index', 'logout', 'accion', 'administrar-cuentas', 'web'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['loginBackEnd'],
                    ],
                    [
                        'actions' => ['administrar-cuentas'],
                        'allow' => true,
                        'roles' => ['parametros'],
                    ],
                    [
                        'actions' => ['web'],
                        'allow' => true,
                        'roles' => ['directo'],
                    ],
                    [
                        'actions' => ['accion'],
                        'allow' => true,
                        'roles' => ['accion'],
                    ]
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

    public function getConfig()
    {
        return Config::find()->one();
    }

    public function getDirecto()
    {
        return Directo::find()->one();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $config = $this->config;

        $detect = new MobileDetect();

        $msgUnete['whatsapp'] = $config->mensaje_whatsapp;
        $msgUnete['twitter']  = $config->mensaje_twitter;

        $directo = $this->directo;

        return $this->render('index', [
            'configuracionAcciones' => [
                'accion' => $this->config->accion
            ],
            'detect' => $detect,
            'msgUnete' => $msgUnete,
            'directo' => $directo
        ]);
    }

    public function actionWeb($config)
    {
        if ($config != 'directo' && $config != 'proxima-partida' && $config != 'cambiar-contrasena') {
            throw new BadRequestHttpException('Configuración inválida.');
        }

        if ($config == 'directo') {
            $mensajePorDefecto = '¡¡Estamos en directo ahora mismo, ven a vernos!! Skeletons\' Trap https://skeletons-trap.herokuapp.com/';

            $directo = $this->directo;
            $image   = null;

            if ($directo) {
                $directo->scenario = Directo::ESCENARIO_UPDATE;
            }

            $model = ($directo ?: new Directo([
                'marcador_propio'   => 0,
                'marcador_oponente' => 0,
                'mensaje_twitter' => $mensajePorDefecto,
                'mensaje_whatsapp' => $mensajePorDefecto,
            ]));
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');

            $validate = false;

            if ($model->file) {
                $validate = ImageFile::upload($model->file, $model, 'logo');
            } else {
                $validate = $model->validate();
            }

            if ($validate && $model->save(false)) {
                \Yii::$app->session->setFlash('success', 'Directo modificado correctamente.');

                return $this->redirect(['index']);
            }
        }

        return $this->render($config, [
            'model' => $model,
            'image' => $image
        ]);
    }

    public function actionAccion($activar)
    {
        $config = $this->config;
        $config->accion = ($activar == 'n' ? null : $activar);

        if (!$config->validate()) {
            throw new BadRequestHttpException('Acción de configuración inválida.');
        }

        if ($activar == 'n') {
            $directo = $this->directo;

            if ($directo) {
                $this->directo->delete();
            }
        }

        $config->save();

        return $this->redirect(['index']);
    }

    public function actionAdministrarCuentas()
    {
        $config = $this->config;

        $model = ($config ?: new Config());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', 'Configuración cambiada correctamente.');

            return $this->redirect(['index']);
        }

        return $this->render('administrar-cuentas', [
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
