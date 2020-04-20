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