<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>
    </div>

    <form action="" method="" name="" id="" class="form-row">
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
                <input type="text" name="cedula" id="cedula" class="form-control form-control-sm" value="<?php echo $_SESSION['usuario']['cedula'];?>" autocomplete="off"/>
            </div>
        </div>

        <!-- NOMBRE 1 -->
        <div class="col-sm-6 col-lg-3 col-xl-2">
            <div class="form-group mb-2">
                <label for="nombre_1" class="small m-0">Primer nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre_1" id="nombre_1" class="form-control form-control-sm" value="<?php echo $_SESSION['usuario']['nombre1'];?>" autocomplete="off"/>
            </div>
        </div>

        <!-- NOMBRE 2 -->
        <div class="col-sm-6 col-lg-3 col-xl-2">
            <div class="form-group mb-2">
                <label for="nombre_2" class="small m-0">Segundo nombre</label>
                <input type="text" name="nombre_2" id="nombre_2" class="form-control form-control-sm"  value="<?php echo $_SESSION['usuario']['nombre2'];?>" autocomplete="off"/>
            </div>
        </div>

        <!-- APELLIDO 1 -->
        <div class="col-sm-6 col-lg-3 col-xl-2">
            <div class="form-group mb-2">
                <label for="apellido_1" class="small m-0">Primer Apellido <span class="text-danger">*</span></label>
                <input type="text" name="apellido_1" id="apellido_1" class="form-control form-control-sm" value="<?php echo $_SESSION['usuario']['apellido1'];?>" autocomplete="off"/>
            </div>
        </div>

        <!-- APELLIDO 2 -->
        <div class="col-sm-6 col-lg-3 col-xl-2">
            <div class="form-group mb-2">
                <label for="apellido_2" class="small m-0">Segundo Apellido</label>
                <input type="text" name="apellido_2" id="apellido_2" class="form-control form-control-sm" value="<?php echo $_SESSION['usuario']['apellido2'];?>" autocomplete="off"/>
            </div>
        </div>

        <!-- SEXO -->
        <div class="col-sm-6 col-lg-3 col-xl-2">
            <div class="form-group mb-2">
                <label for="sexo" class="small m-0">Sexo <span class="text-danger">*</span></label>
                <select name="sexo" id="sexo" class="custom-select custom-select-sm">
                    <option value="">Elija una opción</option>
                    <option value="M" <?php if ($_SESSION['usuario']['sexo'] == 'M') echo 'selected'; ?> >Masculino</option>
                    <option value="F" <?php if ($_SESSION['usuario']['sexo'] == 'F') echo 'selected'; ?>>Femenino</option>
                    <option value="I" <?php if ($_SESSION['usuario']['sexo'] == 'I') echo 'selected'; ?>>Indefinido</option>
                </select>
            </div>
        </div>

        <!-- FECHA DE NACIMIENTO -->
        <div class="col-sm-6 col-lg-3 col-xl-2">
            <div class="form-group mb-2">
                <label for="fecha_n" class="small m-0">Fecha de nacimiento <span class="text-danger">*</span></label>
                <input type="date" name="fecha_n" id="fecha_n" class="form-control form-control-sm" value="<?php echo $_SESSION['usuario']['fecha_n']; ?>"/>
            </div>
        </div>

        <!-- LUGAR DE NACIMIENTO -->
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="form-group mb-2">
                <label for="lugar_n" class="small m-0">Lugar de nacimiento</label>
                <input type="text" name="lugar_n" id="lugar_n" class="form-control form-control-sm" placeholder="Ingrese el lugar de nacimiento" autocomplete="off"/>
            </div>
        </div>

        <!-- EDAD -->
        <div class="col-sm-6 col-lg-2 col-xl-1">
            <div class="form-group mb-2">
                <label for="edad" class="small m-0">Edad</label>
                <input type="text" name="edad" id="edad" class="form-control form-control-sm text-center localStorage" value="0" readonly="true"/>
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
        
        <div class="col-sm-12 text-center mt-2">
            <button class="btn btn-sm btn-info"><i class="fas fa-save"></i><span class="ml-1">Guardar</span></button>
        </div>
    </form>
</div>

<!-- PASAR DATOS DE PHP A JAVASCRIPT -->
<script> let url = '<?php echo SERVERURL; ?>'; </script>
<script>
    console.log(<?php echo json_encode($_SESSION['usuario']);?>);
</script>
