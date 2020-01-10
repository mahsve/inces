function establecer_tabla() {
    numberPage = 1;
    let quantityTotal       = 50;
    let totalPage           = quantityTotal / 10;
    totalPage           = Math.ceil(totalPage);
    
    let contenedor      = "";
    let contenedorTodo  = "";

    // Procedemos a desactivar el boton de anterior si ya no hay mas paginas anteriores.
    if (numberPage == 1) contenedorTodo += '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-left"></i></a></li><li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>';
    else contenedorTodo += '<li class="page-item first"><a class="page-link texto-claro" href="#"><i class="fas fa-angle-double-left"></i></a></li><li class="page-item subtract"><a class="page-link texto-claro" href="#"><i class="fas fa-angle-left"></i></a></li>';

    // Creamos un limite de botones en la paginación de 3 anteriores(Si hay disponibles)
    contadorR = 0;
    for (let i = 3; i > 0; i--) {
        contadorR--;
        let resta = parseInt(numberPage)-i;
        if (resta >= 1)
            contenedor += '<li class="page-item"><a class="page-link change texto-claro" href="#" pagina='+resta+'>'+resta+'</a></li>';
    }

    // Agregamos el numero de paginación actual en la que se encuentra el usuario.
    contenedor += '<li class="page-item active change"><a class="page-link" href="#" pagina='+numberPage+'>'+numberPage+'</a></li>';

    // Creamos un limite de botones en la paginación de 3 siguientes (Si hay disponibles).
    contadorS = 0;
    for (let i = 1; i < 4; i++) {
        contadorS++;
        let suma = parseInt(numberPage)+i;
        if (suma <= totalPage)
            contenedor += '<li class="page-item"><a class="page-link change texto-claro" href="#" pagina='+suma+'>'+suma+'</a></li>';
    }

    // Si hay paginas disponibles mas alla del limite establecido, si procede a señalar con 3 puntos suspensivos
    totalAnteriores = contadorR-1+parseInt(numberPage);
    if (totalAnteriores >= 1)
        contenedorTodo += '<li class="page-item"><a class="page-link change texto-claro" href="#">...</a></li>';

    contenedorTodo += contenedor;
    
    totalSiguientes = contadorS+1+parseInt(numberPage);
    if (totalSiguientes <= totalPage)
        contenedorTodo += '<li class="page-item"><a class="page-link change texto-claro" href="#">...</a></li>';

    // Procedemos a desactivar el boton de siguiente si ya no hay mas paginas siguientes.
    if (numberPage == totalPage) contenedorTodo += '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li><li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a></li>';
    else contenedorTodo += '<li class="page-item add"><a class="page-link texto-claro" href="#"><i class="fas fa-angle-right"></i></a></li><li class="page-item last"><a class="page-link texto-claro" href="#"><i class="fas fa-angle-double-right"></i></a></li>';
    
    $("#paginacion").html(contenedorTodo);
}