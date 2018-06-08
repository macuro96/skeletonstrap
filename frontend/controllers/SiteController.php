<?php
namespace frontend\controllers;

use Yii;
use Detection\MobileDetect;

use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

use common\models\Config;
use common\models\Directo;
use common\models\Usuarios;
use common\models\LoginForm;
use common\models\ZonasHorarias;
use common\models\Nacionalidades;
use common\models\SolicitudesLucha;
use frontend\models\VerificarForm;

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
                'only' => ['logout', 'verificar', 'perfil', 'cambiar-info-perfil', 'dar-baja'],
                'rules' => [
                    [
                        'actions' => ['verificar'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'cambiar-info-perfil', 'perfil', 'dar-baja'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'dar-baja' => ['post'],
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

    public function enviarCorreoAdmin($asunto, $htmlBody, $textBody)
    {
        return Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo(Yii::$app->params['adminEmail'])
                        ->setSubject($asunto)
                        ->setHtmlBody($htmlBody)
                        ->setTextBody($textBody)
                        ->send();
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $config = Config::find()->one();
        $detect = new MobileDetect();

        switch ($config->accion) {
            case 'd':
                $directo = Directo::find()->one();

                var_dump($directo->getLogo());
                die();

                $eventoPartida = $this->renderPartial('_directo', [
                    'detect' => $detect,
                    'titulo' => $directo->titulo,
                    'subtitulo' => $directo->subtitulo,
                    'msgTwitter' => $directo->mensaje_twitter,
                    'msgWhatsapp' => $directo->mensaje_whatsapp,
                    'marcadorPropio' => $directo->marcador_propio,
                    'marcadorOponente' => $directo->marcador_oponente,
                    'nombreEquipoOponente' => $directo->clan->nombre,
                    'imagenLogoOponente' => $directo->logo
                ]);
                break;

            case 'p':
                $eventoPartida = $this->render('_proximaPartida', [
                    'detect' => $detect,
                    'msgTwitter' => 'prueba'
                ]);
                break;

            default:
                $eventoPartida = '';
                break;
        }

        return $this->render('index', [
            'detect' => $detect,
            'config' => $config,
            'eventoPartida' => $eventoPartida
        ]);
    }

    /**
     * Envía una invitación de un nuevo usuario a los administradores.
     * @return mixed
     */
    public function actionUnete()
    {
        $model = new Usuarios([
            'scenario' => Usuarios::ESCENARIO_UNETE
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
            \Yii::$app->session->setFlash('success', 'Se ha enviado la petición al administrador correctamente. Correo suministrado: <b>' . Html::encode($model->correo) . '</b>');

            return $this->redirect(['index']);
        }

        return $this->render('unete', [
            'model' => $model,
            'nacionalidades' => $nacionalidades,
            'zonasHorarias' => $zonasHorarias
        ]);
    }

    /**
     * Envía una invitación de un nuevo usuario a los administradores.
     * @return mixed
     */
    public function actionLuchar()
    {
        $model = new SolicitudesLucha([
            'scenario' => SolicitudesLucha::ESCENARIO_LUCHA
        ]);

        $nacionalidadesDatos = Nacionalidades::find()
                                             ->orderBy('pais ASC')
                                             ->asArray()
                                             ->all();

        $nacionalidades = [];

        foreach ($nacionalidadesDatos as $key => $value) {
            $idNacionalidad   = $value['id'];
            $paisNacionalidad = $value['pais'];

            $nacionalidades[$idNacionalidad] = $paisNacionalidad;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', 'Se ha enviado la petición al administrador correctamente. Correo suministrado: <b>' . Html::encode($model->correo) . '</b>');

            return $this->redirect(['index']);
        }

        return $this->render('luchar', [
            'model' => $model,
            'nacionalidades' => $nacionalidades,
        ]);
    }

    /**
     * Loguea un usuario
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionPerfil()
    {
        return $this->render('perfil');
    }

    public function actionCambiarInfoPerfil()
    {
        $model = \Yii::$app->user->identity;

        if ($model) {
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
                \Yii::$app->session->setFlash('success', 'Los datos han sido actualizados correctamente. Puede cerrar, la ventana.');
            }

            return $this->render('cambiarInfoPerfil', [
                'model' => $model,
                'nacionalidades' => $nacionalidades,
                'zonasHorarias' => $zonasHorarias,
            ]);
        } else {
            throw new BadRequestHttpException('No hay ningún usuario logueado');
        }
    }

    public function actionDarBaja()
    {
        if (Yii::$app->request->post()) {
            $usuario = \Yii::$app->user->identity;

            if ($usuario) {
                if ($this->enviarCorreoAdmin('Usuario de baja', 'El usuario <b>' . $usuario->nombre . '</b>, con TAG ' . $usuario->jugadores->tag . 'se ha dado de baja.', 'El usuario ' . $usuario->nombre . ', con TAG ' . $usuario->tag . 'se ha dado de baja.')) {
                    $usuario->delete();

                    \Yii::$app->session->setFlash('success', 'La cuenta ha sido dada de baja correctamente.');
                    \Yii::$app->user->logout();

                    return $this->goHome();
                }
            }
        }
    }

    /**
     * Verifica un usuario válido
     * @param  string $auth codigo de autenticación dado en el correo de verificación.
     * @return mixed
     */
    public function actionVerificar($auth)
    {
        $usuario = Usuarios::findByVerificado($auth);

        if ($usuario != null) {
            if (!$usuario->estaActivo) {
                throw new BadRequestHttpException('El usuario no está aceptado');
            }

            if (!$usuario->estaVerificado) {
                $usuario->scenario = Usuarios::ESCENARIO_VERIFICAR;

                $model = new VerificarForm();

                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $usuario->password = $model->password;
                    $usuario->save();

                    \Yii::$app->session->setFlash('success', 'La cuenta ha sido verificada correctamente');

                    return $this->redirect(['login']);
                }

                return $this->render('verificar', [
                    'usuario' => $usuario,
                    'model' => $model
                ]);
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Desloguea al usuario logueado actualmente.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
