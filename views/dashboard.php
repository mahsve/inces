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
                <div id="grafica_1" class="w-50 bg-light text-secondary py-3" style="font-size: 30px;"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[0];?></p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="bg-info rounded shadow-sm">
            <div class="d-flex align-items-center text-center" style="font-size: 45px;">
                <div class="w-50"><i class="fas <?php echo $iconos[1]; ?> text-white" style="opacity: .5;"></i></div>
                <div id="grafica_2" class="w-50 bg-light text-secondary py-3" style="font-size: 30px;"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[1];?></p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="bg-info rounded shadow-sm">
            <div class="d-flex align-items-center text-center" style="font-size: 45px;">
                <div class="w-50"><i class="fas <?php echo $iconos[2]; ?> text-white" style="opacity: .5;"></i></div>
                <div id="grafica_3" class="w-50 bg-light text-secondary py-3" style="font-size: 30px;"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[2];?></p>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="bg-info rounded shadow-sm">
            <div class="d-flex align-items-center text-center" style="font-size: 45px;">
                <div class="w-50"><i class="fas <?php echo $iconos[3]; ?> text-white" style="opacity: .5;"></i></div>
                <div id="grafica_4" class="w-50 bg-light text-secondary py-3" style="font-size: 30px;"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
            <p class="px-1 pb-1 m-0 text-white" style="opacity: .7;"><?php echo $descripcion[3];?></p>
        </div>
    </div>

    <div class="col-sm-12">
        <h5 class="font-weight-normal text-secondary text-center text-uppercase">Entrevistas en los ultimos meses</h5>

        <div class="ct-chart"></div>
    </div>
</div>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<link href="<?php echo SERVERURL; ?>styles/chartist/chartist.min.css" rel="stylesheet">
<script src="<?php echo SERVERURL; ?>javascripts/chartist/chartist.min.js"></script>
<script src="<?php echo SERVERURL; ?>javascripts/dashboard.js"></script>