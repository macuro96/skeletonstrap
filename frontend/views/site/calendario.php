<?php

use common\components\RegisterThisCss;

/* @var $this yii\web\View */
/* @var $model common\models\Calendario */

$this->title = 'Calendario';

RegisterThisCss::register($this);

$js = <<<EOT

var meses = [
    'enero',
    'febrero',
    'marzo',
    'abril',
    'mayo',
    'junio',
    'agosto',
    'septiembre',
    'octubre',
    'noviembre',
    'diciembre'
];

var pathname      = window.location.pathname;
var pathNameSplit = pathname.split('/');
var action        = pathNameSplit.slice(0, pathNameSplit.length - 1).join('/');

var csrfToken = $('meta[name="csrf-token"]').attr("content");

var calendario = $('.calendario');

var fechaActual = new Date();
var iMesActual  = fechaActual.getMonth();

var selectorMes           = calendario.find('mes');
var selectorRetrocederMes = calendario.find('#btn-retroceder-mes');
var selectorAvanzarMes    = calendario.find('#btn-avanzar-mes');

selectorMes.text(mes());

selectorRetrocederMes.on('click', function () {
    restarMes();
    selectorMes.text(mes());
    calendario.find('.calendario-contenido').empty();

    console.log(iMesActual);
    cargarEventos(iMesActual);
});

selectorAvanzarMes.on('click', function () {
    sumarMes();
    selectorMes.text(mes());
    calendario.find('.calendario-contenido').empty();

    console.log(iMesActual);
    cargarEventos(iMesActual);
});

function mes() {
    return meses[iMesActual].toUpperCase();
}

function sumarMes() {
    if (iMesActual == 10) {
        iMesActual = 0;
    } else {
        iMesActual++;
    }
}

function restarMes() {
    if (iMesActual == 0) {
        iMesActual = 10;
    } else {
        iMesActual--;
    }
}

function cargarEventos(iMes) {
    $.ajax({
        url: (action + '/datos-calendario'),
        type: 'POST',
        data: {'mes' : iMes},
        success: function (data) {
            if (data.length == 0) {
                sinEventos();
            } else {
                for (let i = 0; i < data.length; i++) {
                    insertarEvento(data[i].etiqueta, data[i].fecha, data[i].hora, data[i].descripcion, data[i].realizado);
                }
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function insertarEvento(etiqueta, dia, hora, descripcion, realizado) {
    var nombreEtiquetaLower = etiqueta.toLowerCase();
    var nombreEtiquetaUpper = etiqueta.toUpperCase();

    var divEvento = $('<div class="row calendario-evento"></div>');

    if (realizado == true) {
        divEvento.addClass('calendario-evento-realizado');
    }

    var divEtiqueta    = $('<div class="col-lg-2 col-md-2 calendario-etiqueta"><div class="row"><div class="col-md-12"></div></div></div>');
    var divDia         = $('<div class="col-lg-1 col-md-2 calendario-dia"><div class="row"><div class="col-md-12"></div></div></div>');
    var divHora        = $('<div class="col-lg-1 col-md-2 calendario-hora"><div class="row"><div class="col-md-12"></div></div></div>');
    var divDescripcion = $('<div class="col-lg-8 col-md-6 calendario-descripcion"><div class="row"><div class="col-md-12"></div></div></div>');

    divEtiqueta.find('.col-md-12').append('<span class="calendario-color-etiqueta color-'+nombreEtiquetaLower+'">'+nombreEtiquetaUpper+'</span>');
    divDia.find('.col-md-12').text(dia);
    divHora.find('.col-md-12').text(hora);
    divDescripcion.find('.col-md-12').text(descripcion.ucfirst());

    divEvento.append(divEtiqueta);
    divEvento.append(divDia);
    divEvento.append(divHora);
    divEvento.append(divDescripcion);

    calendario.find('.calendario-contenido').append(divEvento);
}

function sinEventos() {
    var divSinEventos = $('<div class="row calendario-evento"><div class="col-lg-12 calendario-vacio">NO HAY EVENTOS</div></div>');
    divSinEventos.addClass('calendario-evento-realizado');

    calendario.find('.calendario-contenido').append(divSinEventos);
}

String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}

cargarEventos(iMesActual);

setInterval(function () {
    calendario.find('.calendario-contenido').empty();
    cargarEventos(iMesActual);
}, 10000)

EOT;

$this->registerJs($js);
?>
<div class="row">
    <div class="col-lg-12 calendario">
        <div class="row calendario-titulo">
            <div class="col-lg-1 col-md-4 col-xs-4 calendario-flecha" id="btn-retroceder-mes">
                <span class="glyphicon glyphicon-arrow-left"></span>
            </div>
            <div class="col-lg-10 col-md-4 col-xs-4">
                <h1><mes>ENERO</mes></h1>
            </div>
            <div class="col-lg-1 col-md-4 col-xs-4 calendario-flecha" id="btn-avanzar-mes">
                <span class="glyphicon glyphicon-arrow-right"></span>
            </div>
        </div>
        <div class="row calendario-encabezado hidden-xs hidden-sm">
            <div class="col-lg-2 col-md-2 calendario-col-encabezado">
                <div class="row calendario-nombre-columna">
                    <div class="col-md-12">
                        ETIQUETA
                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-md-2 calendario-col-encabezado">
                <div class="row calendario-nombre-columna">
                    <div class="col-md-12">
                        DIA
                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-md-2 calendario-col-encabezado">
                <div class="row calendario-nombre-columna">
                    <div class="col-md-12">
                        HORA
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 calendario-col-encabezado">
                <div class="row calendario-nombre-columna">
                    <div class="col-md-12">
                        DESCRIPCIÓN
                    </div>
                </div>
            </div>
        </div>
        <div class="row calendario-contenido">
        </div>
    </div>
</div>
