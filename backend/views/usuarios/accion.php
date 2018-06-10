<?php
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\RegisterThisJs;
use common\components\RegisterThisCss;

$this->title = 'Eliminar o expulsar usuarios';

RegisterThisCss::register($this);
RegisterThisJs::register($this);

$accionesPermitidas = [
    'eliminar' => 'Eliminar',
    'expulsar' => 'Expulsar',
    'quitar-expulsion' => 'Quitar expulsión',
];

$accion = (\Yii::$app->request->get('accion') ? \Yii::$app->request->get('accion') : '');

if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'modificarRoles')) {
    $accionesPermitidas['cambiar-rol'] = 'Cambiar rol';
} else {
    if ($accion == 'cambiar-rol') {
        $accion = '';
    }
}

$usuariosAnadidos = $model->usuarios_id;

$primerUsuarioId = (\Yii::$app->request->get('usuario') ? \Yii::$app->request->get('usuario') : 1);
$primerUsuario = (isset($model->usuarios_id[0]) ? $model->usuarios_id[0] : $primerUsuarioId);

if (is_array($usuariosAnadidos) && !empty($usuariosAnadidos)) {
    array_shift($usuariosAnadidos);
} else {
    $usuariosAnadidos = [];
}

$cont = 0;

$errors['noexpulsado'] = [];
$errors['expulsado']   = [];

foreach ($model->errors as $key => $value) {
    $separacion = explode('-', $key);

    if ($separacion[1] == 'noexpulsado') {
        $errors['noexpulsado'][$separacion[0]] = $value[0];
    } elseif ($separacion[1] == 'expulsado') {
        $errors['expulsado'][$separacion[0]] = $value[0];
    }
}

$jsonErrors = ((!empty($errors['noexpulsado']) || !empty($errors['expulsado'])) ? json_encode($errors) : '\'\'');

$arrayJsRoles = '[' . implode(', ', array_map(function ($elemento) {
    return '\'' . $elemento . '\'';
}, $roles)) . ']';

$js = <<<EOT
    var cont = 1;

    var errors = $jsonErrors;
    var roles  = $arrayJsRoles;
    var accion = '$accion';

    $('.anadir-usuario').on('click', function (){
        if ($('.usuario-row').length < 5) {
            var nuevoElemento = $('.usuario-row:last').clone(true);

            nuevoElemento.find('.div-anadir').remove();
            nuevoElemento.find('.div-eliminar').removeClass('hidden');

            nuevoElemento.find('.form-group').removeClass('has-error');
            nuevoElemento.find('.form-group .help-block').remove();

            var select = nuevoElemento.find('select');

            select.attr('id', 'elegirusuarioform-usuarios_id-' + cont++);

            $('.usuario-row:last').after(nuevoElemento);
        }
    });

    $('.eliminar-usuario').on('click', function() {
        $(this).closest('.usuario-row').remove();
    });

    if (errors != '') {
        console.log(errors);
        for (let clave in errors) {
            for (let nID in errors[clave]) {
                var mensaje  = errors[clave][nID];
                var idSelect = '#elegirusuarioform-usuarios_id-' + nID;

                var divDOM = document.createElement('div');
                var div = $(divDOM).addClass('help-block');

                div.text(mensaje);

                var formGroup = $(idSelect).closest('.form-group');

                formGroup.addClass('has-error');
                formGroup.find('.help-block').replaceWith(div);
            }
        }
    }

    function selectRoles(selAccion) {
        $('select[name="ElegirUsuarioForm[rol_cambiar]"]').remove();

        if (selAccion.val() == 'cambiar-rol') {
            var selectRolDOM = document.createElement('select');
            var selectRol = $(selectRolDOM);

            selectRol.attr('id', 'elegirusuarioform-rol_cambiar');
            selectRol.attr('name', 'ElegirUsuarioForm[rol_cambiar]');
            selectRol.attr('aria-required', true);
            selectRol.attr('aria-invalid', false);

            selectRol.addClass('form-control');

            for (let r = 0; r < roles.length; r++) {
                let option = $('<option value='+(r+1)+'>' + roles[r] + '</option>');
                $(selectRol).append(option);
            }

            selAccion.after(selectRol);
        }
    }

    $('#elegirusuarioform-accion').on('change', function () {
        selectRoles($('#elegirusuarioform-accion'));
    });

    if (accion == 'cambiar-rol') {
        selectRoles($('#elegirusuarioform-accion'));
    }
EOT;

$this->registerJs($js);
?>
<div class="row seccion usuarios-form">
    <div class="col-md-5">
        <h2>Eliminar o expulsar usuarios</h2>

        <div>
            <?php $form = ActiveForm::begin(['id' => 'usuarios-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="row usuario-row">
                    <div class="col-md-9 col-xs-9">
                        <?= $form->field($model, "usuarios_id[]")->dropDownList($usuarios,
                            [
                                'value' => $primerUsuario,
                                'id' => 'elegirusuarioform-usuarios_id-' . $cont
                            ]) ?>
                        <?php $cont++ ?>
                    </div>
                    <div class="col-md-1 col-xs-1 div-anadir">
                        <button type="button" class="btn btn-success btn anadir-usuario">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="col-md-1 col-xs-1 div-eliminar hidden">
                        <button type="button" class="btn btn-danger btn eliminar-usuario">
                            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <?php foreach ($usuariosAnadidos as $idUsuario) : ?>
                    <div class="row usuario-row">
                        <div class="col-md-9 col-xs-9">
                                <?= $form->field($model, "usuarios_id[]")->dropDownList($usuarios,
                                [
                                    'value' => $idUsuario,
                                    'id' => 'elegirusuarioform-usuarios_id-' . $cont
                                ]) ?>
                                <?php $cont++ ?>
                        </div>
                        <div class="col-md-1 col-xs-1 div-eliminar">
                            <button type="button" class="btn btn-danger btn eliminar-usuario">
                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'accion')->dropDownList($accionesPermitidas, ['value' => \Yii::$app->request->get('accion')]) ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Hacer acción a usuario', ['class' => 'btn btn-success btn-enviar']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
