<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\Usuarios;
use common\models\Jugadores;
use common\models\ConfigEquipo;
use common\models\ConfigTiempoActualizado;

/**
 * Site controller
 */
class EquipoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['verificar'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'actualizar-miembro'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarios = Usuarios::find()->all();
        $usuariosValidos = Usuarios::validos();

        $clan = ConfigEquipo::findAPI('clan', [
            'tag' => [
                \Yii::$app->params['tagClan']
            ]
        ])[0];

        if (!$clan) {
            throw new BadRequestHttpException('Ha ocurrido un error inesperado. Por favor, inténtelo de nuevo más tarde.');
        }

        return $this->render('index', [
            'jugadores' => $usuariosValidos,
            'clan' => $clan
        ]);
    }

    public function actionActualizarMiembro()
    {
        if (\Yii::$app->request->isAjax) {
            $tag = \Yii::$app->request->post('tag');

            if ($tag) {
                $aBusqueda = [
                    'tag' => [
                        $tag
                    ]
                ];

                if (!ConfigTiempoActualizado::ultimaActualizacionJugador($tag)) {
                    $jugadores = Jugadores::findAPI('jugador', $aBusqueda);

                    if ($jugadores) {
                        $jugador = $jugadores[0];
                        $usuario = Usuarios::find()
                                           ->where(['jugador_id' => $jugador->id])
                                           ->one();

                        return $this->renderPartial('_jugador', [
                            'model' => $usuario
                        ]);
                    }
                }
            }
        }

        return '';
    }
}
