function establecer_tabla(numeroDeLaPagina, cantidadBuscar, totalRegistros) {
    //////////////////////////////////////////////////////////////////////////////////
    let numeroPagina    = parseInt(numeroDeLaPagina);   // NUMERO DE PAGINA ACTUAL
    let cantidadReg     = parseInt(totalRegistros);     // NUMEROS DE REGISTROS DE LA BASE DE DATOS
    let totalPaginas    = cantidadReg / cantidadBuscar; // NUMEROS DE PAGINAS TOTAL DISPONIBLES
    totalPaginas        = Math.ceil(totalPaginas);      // DEFINIR EL NUMERO DE PAGINAS
    //////////////////////////////////////////////////////////////////////////////////
    let contenedor      = "";
    let contenedorTodo  = "";
    //////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////
    // VERIFICAMOS SI ES LA PAGINA UNO Y DESHABILITAR LOS BOTONES DE IR A PRINCIPAL
    if (numeroPagina == 1) {
        contenedorTodo += '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-left"></i></a></li>';
        contenedorTodo += '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>';
    } else {
        contenedorTodo += '<li class="page-item mover" data-pagina="1"><a class="page-link text-info" href="#"><i class="fas fa-angle-double-left"></i></a></li>';
        contenedorTodo += '<li class="page-item mover" data-pagina="'+(numeroPagina-1)+'"><a class="page-link text-info" href="#"><i class="fas fa-angle-left"></i></a></li>';
    }
    //////////////////////////////////////////////////////////////////////////////////
    contadorR = 0;
    for (let i = 2; i > 0; i--) {
        contadorR--;
        let resta = parseInt(numeroPagina)-i;
        if (resta >= 1)
            contenedor += '<li class="page-item mover" data-pagina="'+resta+'"><a class="page-link text-info" href="#" pagina='+resta+'>'+resta+'</a></li>';
    }
    //////////////////////////////////////////////////////////////////////////////////
    let totalAnteriores = contadorR - 1 + parseInt(numeroPagina);
    if (totalAnteriores >= 1)
        contenedorTodo += '<li class="page-item"><a class="page-link text-info" href="#">...</a></li>';
    //////////////////////////////////////////////////////////////////////////////////
    contenedor += '<li class="page-item active" data-pagina="'+numeroPagina+'"><a class="page-link" href="#" pagina='+numeroPagina+'>'+numeroPagina+'</a></li>';
    //////////////////////////////////////////////////////////////////////////////////
    contadorS = 0;
    for (let i = 1; i < 3; i++) {
        contadorS++;
        let suma = parseInt(numeroPagina)+i;
        if (suma <= totalPaginas)
            contenedor += '<li class="page-item mover" data-pagina="'+suma+'"><a class="page-link text-info" href="#">'+suma+'</a></li>';
    }
    //////////////////////////////////////////////////////////////////////////////////
    contenedorTodo += contenedor;
    //////////////////////////////////////////////////////////////////////////////////
    totalSiguientes = contadorS+1+parseInt(numeroPagina);
    if (totalSiguientes <= totalPaginas)
        contenedorTodo += '<li class="page-item"><a class="page-link text-info" href="#">...</a></li>';
    //////////////////////////////////////////////////////////////////////////////////
    // VERIFICAMOS SI ES LA PAGINA FINAL Y DESHABILITAR LOS BOTONES DE IR AL ULTIMO.
    if (numeroPagina == totalPaginas || totalPaginas == 0) {
        contenedorTodo += '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>';
        contenedorTodo += '<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a></li>';
    } else {
        contenedorTodo += '<li class="page-item mover" data-pagina="'+(numeroPagina+1)+'"><a class="page-link text-info" href="#"><i class="fas fa-angle-right"></i></a></li>';
        contenedorTodo += '<li class="page-item mover" data-pagina="'+totalPaginas+'"><a class="page-link text-info" href="#"><i class="fas fa-angle-double-right"></i></a></li>';
    }
    //////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////
    $("#paginacion").html(contenedorTodo);
    $('#paginacion .page-item.active').click(function (e) {
        e.preventDefault();
    });
}