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
});

selectorAvanzarMes.on('click', function () {
    sumarMes();
    selectorMes.text(mes());
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
    // 0  -> Enero
    // 10 -> Diciembre

    $.ajax({
        url: (action + '/datos-calendario'),
        type: 'POST',
        data: {'mes' : iMes},
        success: function (data) {
            console.log('success');
            console.log(data);
        },
        error: function (data) {
            console.log('error');
            console.log(data);
        }
    });
}

cargarEventos(1);

function insertarEvento(etiqueta, dia, hora, descripcion) {
    var nombreEtiquetaLower = etiqueta.toLowerCase();
    var nombreEtiquetaUpper = etiqueta.toUpperCase();

    var divEvento = $('<div class="row calendario-evento"></div>');

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
    calendario.find('.calendario-contenido').append(divSinEventos);
}

String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}

//insertarEvento('liga', '10/01/2018', '12:00 AM', 'ansdfjkasdnf');
//sinEventos();
