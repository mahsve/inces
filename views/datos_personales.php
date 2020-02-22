<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>
    </div>

    <form name="formulario" id="formulario" class="position-relative">
        <div class="form-row mt-3">
            <!-- NACIONALIDAD -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="nacionalidad" class="small m-0">Nacionalidad <span class="text-danger">*</span></label>
                    <select name="nacionalidad" id="nacionalidad" class="custom-select custom-select-sm localStorage">
                        <option value="">Elija una opción</option>
                        <option value="V">Venezolano</option>
                        <option value="E">Extranjero</option>
                    </select>
                </div>
            </div>

            <!-- CEDULA -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="cedula" class="small m-0">Cédula <span class="text-danger">*</span></label>
                    <input type="text" name="cedula" id="cedula" class="form-control form-control-sm localStorage" placeholder="Ingrese la cédula" autocomplete="off"/>
                </div>
            </div>

            <!-- NOMBRE 1 -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre_1" id="nombre_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el nombre" autocomplete="off"/>
                </div>
            </div>

            <!-- NOMBRE 2 -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="nombre_2" class="small m-0">Segundo nombre</label>
                    <input type="text" name="nombre_2" id="nombre_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el nombre" autocomplete="off"/>
                </div>
            </div>

            <!-- APELLIDO 1 -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                    <input type="text" name="apellido_1" id="apellido_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el apellido" autocomplete="off"/>
                </div>
            </div>

            <!-- APELLIDO 2 -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                    <input type="text" name="apellido_2" id="apellido_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el apellido" autocomplete="off"/>
                </div>
            </div>

            <!-- SEXO -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                    <select name="sexo" id="sexo" class="custom-select custom-select-sm localStorage">
                        <option value="">Elija una opción</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="I">Indefinido</option>
                    </select>
                </div>
            </div>

            <!-- FECHA DE NACIMIENTO -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="fecha_n" class="small m-0">Fecha de nacimiento <span class="text-danger">*</span></label>
                    <input type="text" name="fecha_n" id="fecha_n" class="form-control form-control-sm localStorage" placeholder="aaaa-mm-dd" data-date-format="yyyy-mm-dd" style="background: #ffffff;" readonly/>
                </div>
            </div>

            <!-- LUGAR DE NACIMIENTO -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="form-group mb-2">
                    <label for="lugar_n" class="small m-0">Lugar de nacimiento</label>
                    <input type="text" name="lugar_n" id="lugar_n" class="form-control form-control-sm localStorage" placeholder="Ingrese el lugar de nacimiento" autocomplete="off"/>
                </div>
            </div>

            <!-- EDAD -->
            <div class="col-sm-6 col-lg-2 col-xl-1">
                <div class="form-group mb-2">
                    <label for="edad" class="small m-0">Edad</label>
                    <input type="text" name="edad" id="edad" class="form-control form-control-sm text-center localStorage" value="0" readonly="true"/>
                </div>
            </div>

            <!-- OCUPACION -->
            <div class="col-sm-6 col-lg-6 col-xl-3">
                <div class="form-group mb-2">
                    <label for="ocupacion" class="small m-0">Ocupación <span class="text-danger">*</span></label>
                    <select name="ocupacion" id="ocupacion" class="custom-select custom-select-sm localStorage">
                        <option value="">Elija una opción</option>
                    </select>
                </div>
            </div>

            <!-- TELEFONO 1 -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="telefono_1" class="small m-0">Teléfono 1 <span class="text-danger">*</span></label>
                    <input type="text" name="telefono_1" id="telefono_1" class="form-control form-control-sm localStorage" placeholder="Ingrese el telefono" autocomplete="off"/>
                </div>
            </div>
            
            <!-- TELEFONO 2 -->
            <div class="col-sm-6 col-lg-3 col-xl-2">
                <div class="form-group mb-2">
                    <label for="telefono_2" class="small m-0">Teléfono 2 </label>
                    <input type="text" name="telefono_2" id="telefono_2" class="form-control form-control-sm localStorage" placeholder="Ingrese el telefono" autocomplete="off"/>
                </div>
            </div>

            <!-- CORREO -->
            <div class="col-sm-6 col-lg-5 col-xl-3">
                <div class="form-group mb-2">
                    <label for="correo" class="small m-0">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control form-control-sm localStorage" placeholder="Ingrese el correo" autocomplete="off"/>
                </div>
            </div>
        </div>

        <div class="form-row mt-3">
            <!-- TITULO -->
            <div class="col-sm-12">
                <h4 class="font-weight-normal text-secondary text-center text-uppercase">Datos de ubicación</h4>    
            </div>

            <!-- ESTADO -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="form-group mb-2">
                    <label for="estado" class="small m-0">Estado <span class="text-danger">*</span></label>
                    <select name="estado" id="estado" class="custom-select custom-select-sm localStorage">
                        <option value="">Elija una opción</option>
                    </select>
                </div>
            </div>

            <!-- CIUDAD -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="form-group mb-2">
                    <label for="ciudad" class="small m-0">Ciudad <span class="text-danger">*</span></label>
                    <select name="ciudad" id="ciudad" class="custom-select custom-select-sm localStorage">
                        <option value="">Elija un estado</option>
                    </select>
                </div>
            </div>

            <!-- MUNICIPIO -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="form-group mb-2">
                    <label for="municipio" class="small m-0">Municipio</label>
                    <select name="municipio" id="municipio" class="custom-select custom-select-sm localStorage">
                        <option value="">Elija un estado</option>
                    </select>
                </div>
            </div>

            <!-- PARROQUIA -->
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="form-group mb-2">
                    <label for="parroquia" class="small m-0">Parroquia</label>
                    <select name="parroquia" id="parroquia" class="custom-select custom-select-sm localStorage">
                        <option value="">Elija un municipio</option>
                    </select>
                </div>
            </div>

            <!-- DIRECCION -->
            <div class="col-sm-12">
                <div class="form-group mb-2">
                    <label for="direccion" class="small m-0">Dirección <span class="text-danger">*</span></label>
                    <textarea name="direccion" id="direccion" class="form-control form-control-sm localStorage" placeholder="Ingrese la dirección del hogar"></textarea>
                </div>
            </div>
        </div>

        <div class="col-sm-12 text-center mt-2">
            <button class="btn btn-sm btn-info" id="guardar_datos"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
        </div>

        <div id="carga_espera" class="position-absolute rounded w-100 h-100" style="top: 0px; left: 0px;">
            <div class="d-flex justify-content-center align-items-center w-100 h-100">
                <p class="h4 text-white m-0"><i class="fas fa-spinner fa-spin mr-3"></i><span>Cargando algunos datos...</span></p>
            </div>
        </div>
    </form>
</div>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script src="<?php echo SERVERURL; ?>javascripts/datos_personales.js"></script>