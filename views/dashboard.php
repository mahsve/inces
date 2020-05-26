<?php
// DASHBOARD DEL ADMINISTRADOR
if ($_SESSION['usuario']['tipo_persona'] == 'A') {
    $iconos = ['fa-user-graduate', 'fa-user-graduate', 'fa-book', 'fa-chalkboard-teacher'];
    $descripcion = ['Aprendices activos', 'Total aprendices', 'Oficios disponibles', 'Facilitadores'];
// DASHBOARD DEL PERSONAL ADMINISTRATIVO
}

// DASHBOARD DEL LOS APRENDICES

// DASHBOARD DEL LOS FACILITADORES
?>

<h4 class="text-uppercase text-secondary font-weight-normal mb-3">Panel principal</h4>

<div class="form-row">
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="bg-info rounded shadow-sm">
            <div class="d-flex align-items-center text-center" style="font-size: 45px;">
                <div class="w-50"><i class="fas <?php echo $iconos[0]; ?> text-white" style="opacity: .5;"></i></div>
                <div class="w-50 bg-light text-secondary py-3" style="font-size: 30px;">60</div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[0];?></p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="bg-info rounded shadow-sm">
            <div class="d-flex align-items-center text-center" style="font-size: 45px;">
                <div class="w-50"><i class="fas <?php echo $iconos[1]; ?> text-white" style="opacity: .5;"></i></div>
                <div class="w-50 bg-light text-secondary py-3" style="font-size: 30px;">5000</div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[1];?></p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="bg-info rounded shadow-sm">
            <div class="d-flex align-items-center text-center" style="font-size: 45px;">
                <div class="w-50"><i class="fas <?php echo $iconos[2]; ?> text-white" style="opacity: .5;"></i></div>
                <div class="w-50 bg-light text-secondary py-3" style="font-size: 30px;">12</div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[2];?></p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="bg-info rounded shadow-sm">
            <div class="d-flex align-items-center text-center" style="font-size: 45px;">
                <div class="w-50"><i class="fas <?php echo $iconos[3]; ?> text-white" style="opacity: .5;"></i></div>
                <div class="w-50 bg-light text-secondary py-3" style="font-size: 30px;">100</div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[3];?></p>
        </div>
    </div>

    <div class="col-sm-12 col-lg-9 mb-3">
        <div class="rounded shadow-sm" style="height: 300px;">
            <h3 style="padding-top: 40px;">Grafica</h3>
        </div>
    </div>
    <div class="col-sm-12 col-lg-3 mb-3">
        <div class="rounded shadow-sm" style="height: 300px;">
        </div>
    </div>
</div>