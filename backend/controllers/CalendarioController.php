<?php
namespace backend\controllers;

use Yii;

use yii\db\Expression;

use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\Roles;
use common\models\Calendario;
use common\models\EventoEtiquetas;

/**
 * Site controller
 */
class CalendarioController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['verBackEndCalendario'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['programarEvento'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['modificarEvento'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['eliminarEvento'],
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
                    'logout' => ['delete'],
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

    private function eventosUsuarioQuery()
    {
        return Calendario::find()
                         ->select(new Expression('id, etiqueta, fecha, hora, imagen, visibilidad, (substr(descripcion, 1, 16) || \'...\') as descripcion'))
                         ->where(\Yii::$app->user->identity->roles[0]->id . ' <= visibilidad')
                         ->orWhere('visibilidad is null')
                         ->orderBy('fecha DESC, hora DESC')
                         ->limit(20);
    }

    private function eventosUsuario()
    {
        return $this->eventosUsuarioQuery()->all();
    }

    private function listaDatosEventos($eventosDatos)
    {
        $eventosDatos = $eventosDatos->select(new Expression('id, etiqueta, fecha, hora + interval \'2h\' as hora, imagen, visibilidad, (substr(descripcion, 1, 16) || \'...\') as descripcion'))->all();

        $eventos = [];

        foreach ($eventosDatos as $key => $value) {
            $idEventos     = $value['id'];
            $nombreEventos = EventoEtiquetas::findOne($value['etiqueta'])->nombre . ': ' . date_format(date_create($value['fecha']), 'd-m-Y') . ' - ' . $value['hora'] . ' -> ' . $value['descripcion'];

            $eventos[$idEventos] = $nombreEventos;
        }

        return $eventos;
    }

    private function listaDatos()
    {
        $rolesDatos = Roles::find()
                           ->orderBy('id')
                           ->where(\Yii::$app->user->identity->roles[0]->id . ' <= id')
                           ->all();

        $roles = [];
        $roles[0] = null;

        foreach ($rolesDatos as $key => $value) {
            $idRoles     = $value['id'];
            $nombreRoles = $value['nombre'];

            $roles[$idRoles] = $nombreRoles;
        }

        $etiquetasDatos = EventoEtiquetas::find()
                                         ->orderBy('nombre')
                                         ->all();

        $etiquetas = [];

        foreach ($etiquetasDatos as $key => $value) {
            $idEtiquetas     = $value['id'];
            $nombreEtiquetas = $value['nombre'];

            $etiquetas[$idEtiquetas] = $nombreEtiquetas;
        }

        return [
            'roles' => $roles,
            'etiquetas' => $etiquetas
        ];
    }

    public function actionIndex()
    {
        $eventos = $this->listaDatosEventos($this->eventosUsuarioQuery());

        return $this->render('index', [
            'eventos' => $eventos,
        ]);
    }

    public function actionCreate()
    {
        $model = new Calendario([
            'fecha' => date('d-m-Y'),
        ]);

        $datos = $this->listaDatos();

        if ($model->load(Yii::$app->request->post())) {
            $dateArray = explode('-', $model->fecha);
            $fechaNueva = "{$dateArray[2]}-{$dateArray[1]}-{$dateArray[0]}";

            $model->fecha = $fechaNueva;

            date_default_timezone_set(\Yii::$app->formatter->timeZone);
            $model->hora = gmdate('H:i', strtotime($model->hora));

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Evento guardado correctamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'etiquetas' => $datos['etiquetas'],
            'rolesVisibilidad' => $datos['roles'],
        ]);
    }

    public function actionUpdate($evento)
    {
        $model = $this->eventosUsuarioQuery()->where(['id' => $evento])->select(new Expression('id, etiqueta, fecha, hora + interval \'2h\' as hora, imagen, visibilidad, (substr(descripcion, 1, 16) || \'...\') as descripcion'))->one();

        if ($model->descripcion == '...') {
            $model->descripcion = null;
        }

        if (!$model) {
            throw new BadRequestHttpException('Evento no válido.');
        }

        $datos = $this->listaDatos();

        $dateArray = explode('-', $model->fecha);
        $fechaNueva = "{$dateArray[2]}-{$dateArray[1]}-{$dateArray[0]}";

        $model->fecha = $fechaNueva;

        if ($model->load(Yii::$app->request->post())) {
            $dateArray = explode('-', $model->fecha);
            $fechaNueva = "{$dateArray[2]}-{$dateArray[1]}-{$dateArray[0]}";

            $model->fecha = $fechaNueva;

            date_default_timezone_set(\Yii::$app->formatter->timeZone);
            $model->hora = gmdate('H:i', strtotime($model->hora));

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Evento actualizado correctamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'etiquetas' => $datos['etiquetas'],
            'rolesVisibilidad' => $datos['roles'],
        ]);
    }

    public function actionDelete()
    {
        if (\Yii::$app->request->post()) {
            $model = $this->eventosUsuarioQuery()
                          ->where(['id' => \Yii::$app->request->post('evento')])
                          ->one();

            if (!$model) {
                throw new BadRequestHttpException('Evento no válido.');
            }

            $model->delete();

            \Yii::$app->session->setFlash('success', 'Evento borrado correctamente.');
        }
        return $this->redirect(['index']);
    }
}
