$(function () {
    // FUNCION PARA MOSTRAR Y ESCONDER EL MENU EN PANTALLAS PEQUEÑAS.
    $('#menu-manager').click(gestionMenu);
    function gestionMenu () {
        // SI TIENE LA CLASE MOSTRAR LA QUITA.
        if ($('#sidebar').hasClass('show'))
            $('#sidebar').removeClass('show');
        // SI NO LA TIENE LA COLOCA.
        else
            $('#sidebar').addClass('show');
    }

    // INICIALIZAMOS ALGUNAS FUNCIONES DE LOS FRAMEWORK Y LIBRERIAS.
    $('.dropdown-toggle').dropdown();
    $("[data-toggle='popover']").popover();
});