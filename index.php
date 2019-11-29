<!DOCTYPE html>
<html lang="es">
<head>
    <title>Iniciar sesión | INCES</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="images/app/favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="styles/fontawesome/css/all.css">
    <link rel="stylesheet" href="styles/homepage.css">
</head>

<body>
    <div class="bg-white py-0">
        <div id="contenedor_imagen" class="container position-relative">
            <img src="images/homepage/ministerio.png" id="ministerio">
        </div>
    </div>

    <div class="bg-info">
        <div class="container">
            <nav class="navbar navbar-light p-0">
                <a href="index" class="navbar-brand p-2" id="titulo_pagina">
                    <img src="images/app/logo-invertido.svg" class="d-inline-block align-top my-1">
                </a>

                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-white rounded px-2 py-1 mx-1" href="index"><i class="fas fa-home"></i><span class="ml-1">Inicio</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white rounded px-2 py-1 mx-1" href="iniciar"><i class="fas fa-sign-in-alt"></i><span class="ml-1">Intranet</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white rounded px-2 py-1 mx-1" href="#"><i class="fas fa-info-circle"></i><span class="ml-1">Ayuda</span></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        </ol>

        <div class="carousel-inner">
            <div class="carousel-item position_relative active">
                <img src="images/homepage/background.png" class="d-block w-100">
                <img src="images/app/logo.svg" class="position-absolute h-50 img_absolute">
            </div>

            <div class="carousel-item position_relative">
                <img src="images/homepage/background.png" class="d-block w-100">
                <img src="images/homepage/image_1.png" class="position-absolute h-100 img_absolute py-2">
            </div>
        </div>

        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="media container p-3">
        <img src="images/homepage/portuguesa.png" width="120" class="align-self-center mr-3 border border-success rounded-circle img-thumbnail">
        
        <div class="media-body">
            <h5 class="mt-0">INSTITUCIÓN</h5>
            <p class="text-justify small m-0">El INCES es un organismo autónomo con personalidad jurídica y patrimonio propio, adscrito según gaceta oficial N° 40.378, de fecha 25 de marzo de 2014, al Ministerio del Poder Popular para el Proceso Social de Trabajo, creado por Ley el 22 de Agosto de 1959 y reglamentado por Decreto el 11 de Marzo de 1960 bajo la denominación de Instituto Nacional de Cooperación Educativa (INCE). En el 2003 de acuerdo con Decreto publicado en la Gaceta Oficial Nº 37.809 de fecha 03 de Noviembre, se reforma el reglamento de la Ley del INCE, con la finalidad de reorganizarlo y adecuarlo a los intereses del país y al proceso de reconversión industrial, proceso que enmarca posteriormente su concepción y visión, dentro del ámbito de un socialismo abierto y participativo.</p>
        </div>
    </div>

    <hr>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="w-100">
                    <div class="text-center w-100">
                        <img src="images/homepage/mision.png" class="d-inline-block w-75">
                    </div>

                    <h3 class="font-weight-light">Misión</h3>
                    <p class="text-justify small">El Inces es la institución del Estado encargada de la formación y autoformación colectiva, integral, continua y permanente de los trabajadores y las trabajadoras, orientada al desarrollo de sus capacidades para la producción de bienes y prestación de servicios que satisfagan las necesidades del Poder Popular, su incorporación consciente al proceso social de trabajo y la construcción de relaciones laborales justas e igualitarias.</p>
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="w-100">
                    <div class="text-center w-100">
                            <img src="images/homepage/mision.png" class="d-inline-block w-75">
                        </div>

                    <h3 class="font-weight-light">Visión</h3>
                    <p class="text-justify small">Convertirnos en una poderosa herramienta para la transformación y consolidación de una economía soberana y diversificada, siendo referente nacional e internacional de la formación técnica profesional inclusiva y colectiva, con altos niveles de calidad, que forma trabajadores y trabajadoras conscientes de su rol como sujeto social protagónico, con dominio de los procesos productivos y capacidad para generar tecnología e innovación creadora.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-info text-white text-center p-1">
        <p class="small m-0">Instituto Nacional de Capacitación y Educación Socialista - INCES</p>
        <p class="small m-0">Todos los derechos reservados &copy; INCES 2019 | Rif: G-20009922-4</p>
    </footer>

    <script src="javascripts/jquery/jquery-3.4.1.min.js"></script>
    <script src="javascripts/popper/popper.min.js"></script>
    <script src="javascripts/bootstrap/bootstrap.min.js"></script>
</body>
</html>