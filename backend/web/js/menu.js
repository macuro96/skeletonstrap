var bSlideMenu = false;

$(window).resize(function(e){
    if ($(window).width() <= 991) {
        slideUpMenu();
    } else {
        slideDownMenu();
    }
})

$('.expandir').on('click', function(){
    if (!bSlideMenu) {
        slideUpMenu();
    } else {
        slideDownMenu();
    }
});

function slideUpMenu() {
    $('.contenedor-menu .menu > .opciones').slideUp();

    $('.expandir span').removeClass('glyphicon-chevron-down');
    $('.expandir span').addClass('glyphicon-chevron-up');

    bSlideMenu = true;
}

function slideDownMenu() {
    $('.contenedor-menu .menu > .opciones').slideDown();

    $('.expandir span').removeClass('glyphicon-chevron-up');
    $('.expandir span').addClass('glyphicon-chevron-down');

    bSlideMenu = false;
}
