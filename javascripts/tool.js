function calcularEdad (fechaA, fechaN) {
    let edad    = 0;
    let day     = fechaN.substr(0,2);
    let month   = fechaN.substr(3,2);
    let year    = fechaN.substr(6,4);
    let dayA    = fechaA.substr(0,2);
    let monthA  = fechaA.substr(3,2);
    let yearA   = fechaA.substr(6,4);

    if (year <= yearA) {
        edad = yearA - year;
        if (month > monthA) {
            if (edad != 0) { edad--; }
        } else if (month == monthA) {
            if (day > dayA) { if (edad != 0) { edad--; } }
        }
    }
    return edad;
}

function abreviarDescripcion (descripcion, total_caracteres) {
    // GUARDAMOS LOS DATOS EN UNA VARIABLE
    let descripcion_completa = descripcion;
    
    // VERIFICAMOS QUE NO SOBREPASE EL MAXIMODE CARACTERES ESTABLECIDOS
    if (descripcion_completa.length > total_caracteres) {
        // TOMAMOS SOLO LOS CARACTERES ESTABLECIDOS
        descripcion_completa = descripcion_completa.substr(0, total_caracteres);
        // VERIFICAMOS QUE LA ULTIMA POSICION NO SEA UN ESPACIO (ERROR VISUAL).
        if (descripcion_completa[descripcion_completa.length - 1] == ' ') { descripcion_completa = descripcion_completa.substr(0, (total_caracteres - 1)); }
        // SE LE CONCATENA ... PARA DESCRIBIR QUE FUE RECORTADA LA DESCRIPCION
        descripcion_completa += '...';
    }
    return descripcion_completa;
}