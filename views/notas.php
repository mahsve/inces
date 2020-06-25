<div id="info_table">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-secondary text-uppercase font-weight-normal mb-0"><?php echo $titulo; ?></h4>
    </div>
    
    <div class="form-row">
        <div class="col-sm-12 col-md-6 align-self-center">
            <div class="tab-asignatura btn-info rounded px-3 py-2" style="cursor: pointer;">
                <h3>Asistente administrativo</h3>
                <h5>Módulo: Módulo de integridad</h5>
                <h6>Asignatura: Desarrollo del pensamiento</h6>
                <p class="m-0">Sección: 1 | Turno: Matutino</p>
                <p class="m-0">N° de aprendices: 15</p>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 align-self-center">
            <div class="tab-asignatura btn-info rounded px-3 py-2" style="cursor: pointer;">
                <h3>Asistente administrativo</h3>
                <h5>Módulo: Módulo de integridad</h5>
                <h6>Asignatura: Administración de recursos humano</h6>
                <p class="m-0">Sección: 2 | Turno: vespertino</p>
                <p class="m-0">N° de aprendices: 21</p>
            </div>
        </div>
    </div>
</div>

<?php 
$cedulas = [
    'V-27231356',
    'V-28988854',
    'V-28115982',
    'V-30157844',
    'V-27153844',

    'V-28301482',
    'V-30158364',
    'E-27231356',
    'V-28301482',
    'V-27231356',

    'V-27598455',
    'V-27231356',
    'E-28100015',
    'V-27198521',
    'V-28301482',
];
$nombres = [
    'Juan Guillermo Alvares Perez',
    'Jorge Alberto Perez Gonzales',
    'Miguel Alejandro Hernandez Lopez',
    'Luis Alvaro Arangure Rodriguez',
    'Maria Alejandra Garcias',

    'Juan Luis',
    'Pedro Lopez Herrera',
    'Jose Garcias',
    'Maria Jose Fuentes',
    'Patricia Fernanda Alvares',

    'Alberto Garcias',
    'Joset Perez Gonzales',
    'Alejandro Jose Coromoto',
    'Maria Valentina Rusa Arangure',
    'Fabiola Alejandra',
];
$edaded = [
    17,
    17,
    16,
    18,
    17,

    18,
    18,
    16,
    16,
    17,

    17,
    17,
    17,
    16,
    17
];
?>

<?php if ($permisos['registrar'] == 1 OR $permisos['modificar']){ ?>
    <div id="gestion_form" style="display: none;">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
            <h4 id="form_title" class="text-secondary text-uppercase font-weight-normal mb-0"></h4>
            <button type="button" id="show_table" class="botones_formulario btn btn-sm btn-info"><i class="fas fa-reply"></i><span class="ml-1">Regresar</span></button>
        </div>

        <form name="formulario" id="formulario" class="formulario">
            <div class="table-responsive pb-2">
                <table id="listado_tabla" class="table table-borderless table-hover mb-0" style="min-width: 950px;">
                    <thead>
                        <tr class="text-white">
                            <th class="bg-info font-weight-normal px-1 py-2 rounded-left" width="100">Cédula</th>
                            <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                            <th class="bg-info font-weight-normal px-1 py-2 text-center" width="60">Edad</th>
                            
                            <th class="bg-info font-weight-normal px-1 py-2 text-center" width="90">Nota 1</th>
                            <th class="bg-info font-weight-normal px-1 py-2 text-center" width="90">Nota 2</th>
                            <th class="bg-info font-weight-normal px-1 py-2 text-center" width="90">Nota 3</th>
                            <th class="bg-info font-weight-normal px-1 py-2 text-center rounded-right" width="90">Nota 4</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php for ($var = 0; $var < 15; $var++) { ?>
                            <tr class="border-bottom text-secondary">
                                <td class="py-2 px-1"><?php echo $cedulas[$var]; ?></td>
                                <td class="py-2 px-1"><?php echo $nombres[$var]; ?></td>
                                <td class="py-2 px-1 text-center"><?php echo $edaded[$var]; ?></td>
                                <td class="py-1 px-0"><input type="text" name="nota1[]" class="campos_formularios nota1 form-control form-control-sm" autocomplete="off"/></td>
                                <td class="py-1 px-0"><input type="text" name="nota2[]" class="campos_formularios nota2 form-control form-control-sm" autocomplete="off"/></td>
                                <td class="py-1 px-0"><input type="text" name="nota3[]" class="campos_formularios nota3 form-control form-control-sm" autocomplete="off"/></td>
                                <td class="py-1 px-0"><input type="text" name="nota4[]" class="campos_formularios nota4 form-control form-control-sm" autocomplete="off"/></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div id="contenedor-mensaje2"></div>

            <!-- BOTON GUARDAR DATOS -->
            <div class="pt-2 text-center">
                <button id="guardar-datos" type="button" class="botones_formulario btn btn-sm btn-info px-4"><i class="fas fa-save"></i> <span>Guardar</span></button>
            </div>
            <!-- FIN BOTON GUARDAR DATOS -->
        </form>
    </div>
<?php } ?>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/notas.js"></script>