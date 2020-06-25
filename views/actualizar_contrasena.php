<div class="w-100">
    <div class="row justify-content-center h-100 m-0">
        <div class="col-sm-12 col-md-7 col-lg-5 align-self-center">
            <div class="bg-white rounded p-3">
                <h3 class="text-center text-secondary mb-3">Actualizar contraseña</h3>
                <i class="text-secondary small">Debe actualizar su contraseña y elegir un plazo de vigencia.</i>
                
                <form name="formulario" id="formulario" class="formulario">
                    <div class="form-group text-secondary mb-2">
                        <label for="contrasena_1" class="small m-0">Nueva contraseña:</label>
                        <input class="campos_formularios form-control form-control-sm" type="password" name="contrasena_1" id="contrasena_1" required>
                    </div>

                    <div class="form-group text-secondary mb-2">
                        <label for="contrasena_2" class="small m-0">Repita la contraseña:</label>
                        <input class="campos_formularios form-control form-control-sm" type="password" name="contrasena_2" id="contrasena_2" required>
                    </div>

                    <div class="form-group text-secondary mb-2">
                        <label for="tiempo" class="small m-0">Plazo de tiempo</label>
                        <select name="tiempo" id="tiempo" class="campos_formularios custom-select custom-select-sm">
                            <option value="">Elija una opcion</option>
                            <option value="30">30</option>
                            <option value="60">60</option>
                            <option value="90">90</option>
                            <option value="120">120</option>
                            <option value="150">150</option>
                            <option value="180">180</option>
                        </select>
                    </div>

                    <div id="contenedor-mensaje"></div>

                    <div class="pt-2 text-center">
                        <button id="guardar-datos" type="button" class="botones_formulario btn btn-sm btn-info btn-block px-4"><i class="fas fa-save"></i> <span>Guardar</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/actualizar_contrasena.js"></script>