$(function () {
    let vista = 1;
    // $('#datos_aprendiz').html();
    // $('#datos_hogar')
    

    $('#retroceder_form').click(retrocederForm);
    function retrocederForm (e)
    {
        e.preventDefault();
        if (vista == 2)
        {
            $('#datos_aprendiz').show(300);
            $('#datos_hogar').hide(300);
            vista--;
        }
    }

    $('#avanzar_form').click(avanzarForm);
    function avanzarForm (e)
    {
        e.preventDefault();
        if (vista == 1)
        {
            $('#datos_aprendiz').hide(300);
            $('#datos_hogar').show(300);
            vista++;
        }
    }
});