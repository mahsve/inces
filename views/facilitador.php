<div id="info_table" style="">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <h4 class="text-uppercase text-secondary font-weight-normal mb-0"><?php echo $titulo; ?></h4>
        <button type="button" id="show_form" class="btn btn-sm btn-info hide-descrip"><i class="fas fa-plus"></i><span class="ml-1">Registrar</span></button>
    </div>

    <div class="row justify-content-between">
        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="col-sm-12 col-xl-8">
            <div class="form-row">
                <div class="col-sm-6 col-lg-3 col-xl-2 form-group d-flex align-items-center text-info mb-2">
                    <label for="cantidad_a_buscar" class="pr-2 m-0"><i class="fas fa-list-ul"></i></label>
                    <select id="cantidad_a_buscar" class="custom-select custom-select-sm">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-3 col-xl-3 form-group d-flex align-items-center text-info mb-2">
                    <label for="ordenar_por" class="pr-2 m-0"><i class="fas fa-sort-alpha-down"></i></label>
                    <select id="ordenar_por" class="custom-select custom-select-sm">
                        <option value="1">N° informe</option>
                        <option value="2">Cédula</option>
                        <option value="3">Fecha Reg.</option>
                        <option value="4">Nombre</option>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-3 col-xl-2 form-group d-flex align-items-center text-info mb-2">
                    <label for="campo_ordenar" class="pr-2 m-0"><i class="fas fa-sort-numeric-down"></i></label>
                    <select id="campo_ordenar" class="custom-select custom-select-sm">
                        <option value="1">ASC</option>
                        <option value="2">DESC</option>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-3 col-xl-3 form-group d-flex align-items-center text-info mb-2">
                    <label for="buscar_estatus" class="pr-2 m-0"><i class="fas fa-toggle-on"></i></label>
                    <select id="buscar_estatus" class="custom-select custom-select-sm">
                        <option value="">Todos</option>
                        <option value="A">Aceptados</option>
                        <option value="I">Rechazados</option>
                        <option value="E">En espera</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CANTIDAD DE FILAS POR BUSQUEDAS -->
        <div class="col-sm-12 col-xl-4 mb-2">
            <div class="form-group d-flex align-items-center text-info mb-0">
                <label for="campo_busqueda" class="pr-2 m-0"><i class="fas fa-search"></i></label>
                <input type="text" id="campo_busqueda" class="form-control form-control-sm" placeholder="Buscar..." autocomplete="off"/>
                <button type="button" class="btn btn-sm btn-info ml-2" data-toggle="collapse" data-target="#mas_opciones_busquedas" aria-expanded="false" aria-controls="mas_opciones_busquedas"><i class="fas fa-plus"></i></button>
            </div>
        </div>
    </div>

    <div id="mas_opciones_busquedas" class="collapse">
        <div class="card card-body mb-2 px-3 py-2">
            <h5 class="text-secondary text-uppercase">Mas opciones de búsquedas</h5>

            <div class="form-row">
                <div class="col-sm-12 col-lg-6 col-xl-5">
                    <div class="border rounded px-2">
                        <label class="text-secondary pr-2 mb-1">Entre fechas:</label>
                        <div class="form-row align-items-end">
                            <div class="form-group col-sm-12 col-md-5 mb-2">
                                <label for="fechas_desde" class="small m-0">Desde:</label>
                                <input type="date" id="fechas_desde" class="form-control form-control-sm"/>
                            </div>

                            <div class="form-group col-sm-12 col-md-5 mb-2">
                                <label for="fechas_hasta" class="small m-0">Hasta:</label>
                                <input type="date" id="fechas_hasta" class="form-control form-control-sm"/>
                            </div>

                            <div class="col-sm-12 col-md-2 mb-2">
                                <button type="button" class="btn btn-sm btn-info btn-block"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive pb-2">
        <table id="listado_aprendices" class="table table-borderless mb-0" style="min-width: 950px;">
            <thead>
                <tr class="text-white">
                    <th class="bg-info rounded-left font-weight-normal px-1 py-2" width="100">N° informe</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="100">Cédula</th>
                    <th class="bg-info font-weight-normal px-1 py-2">Nombre completo</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="90">Fecha Nac.</th>
                    <th class="bg-info font-weight-normal px-1 py-2 text-center" width="45">Edad</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="150">Oficio</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="80">Turno</th>
                    <th class="bg-info font-weight-normal px-1 py-2" width="80">Estatus</th>
                    <th class="bg-info rounded-right p-0 py-1" width="76"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="10" class="text-center text-secondary border-bottom p-2"><i class="fas fa-ban mr-3"></i>Espere un momento</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row mt-2">
        <div class="col-sm-6 d-flex align-items-center small font-weight-bold text-info">
            Lista desde el <span id="inicio-lista" class="px-1">0</span> hasta el <span id="fin-lista" class="px-1">0</span> de <span id="total-lista" class="px-1">0</span> en total
        </div>

        <div class="col-sm-6">
            <nav aria-label="Page navigation example">
                <ul id="paginacion" class="pagination pagination-sm justify-content-end mb-0">
                    <li class="page-item"><a class="page-link" href="#">Cargando...</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>