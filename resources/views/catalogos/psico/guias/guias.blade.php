<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title>Results In Performance</title>



    <link href="/assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/scss/icons/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/scss/icons/material-design-iconic-font/css/materialdesignicons.min.css">
    <!-- You can change the theme colors from here -->
    <link href="/css/colors/color_RIP.css" id="theme" rel="stylesheet">
    <!--alerts CSS -->
    <!-- <link href="/assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.4/dist/sweetalert2.min.css">
    <!-- Date picker plugins css -->
    <link href="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
</head>

<body>

    <style>
        /* DISEÑO DE SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* VARIABLES DE COLORES USADAS*/
        :root {
            --azul-oscuro: #003d6b;
            --azul-cielo: #009bcf;
            --verde: #88bd23;
            --gris-claro: #f8f9fa;
            --gris-medio: #e9ecef;
            --negro: #1a1a1a;
            --blanco: #ffffff;
        }

        body {
            font-family: "Poppins", sans-serif;

        }

        /* Header Container */
        .header-container {
            width: 100%;
            padding: 1.5rem 2rem;
            background: var(--azul-cielo);
            position: relative;
        }

        /* Layout Principal */
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            display: grid;
            grid-template-columns: 1fr minmax(0, 800px) 1fr;
            align-items: center;
        }

        /* Logo */
        .logo-wrapper {
            grid-column: 1;
            justify-self: start;
        }

        .logo-wrapper img {
            width: 220px;
            height: auto;
            display: block;
        }

        /* Título */
        .title-wrapper {
            grid-column: 2;
            text-align: center;
            padding: 0 1rem;
        }

        .main-title {
            font-size: 3rem;
            color: var(--blanco);
            font-weight: 700;
            margin: 0;
            white-space: nowrap;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        /* Espacio Derecho (para balance) */
        .spacer {
            grid-column: 3;
        }

        /* Efectos y Decoraciones */
        .main-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 3px;
            background: var(--azul-oscuro);
            margin: 0.5rem auto 0;
            border-radius: 2px;
        }

        
        .row {
            margin-left: 10px;
        }

        
        /* .col-9 {
            flex: 0 0 75%;
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
        } */

       
        /* .col-3 {
            flex: 0 0 23%;
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            height: 100%;
          
        } */

        /* Estilos para el contenedor de las tarjetas */
        .card-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Estilos para las tarjetas/guías */
        #guia1,
        #guia2,
        #guia3 {
            border: 1px solid #e0e0e0;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            width: 100%;
            transition: all 0.3s ease;
            background: #fff;
        }

        #guia1:hover,
        #guia2:hover,
        #guia3:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para los radio sean color verde */
        input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border: 1px solid var(--negro);
            /* Color verde */
            border-radius: 50%;
            outline: none;
            cursor: pointer;
            position: relative;
            margin-right: 8px;
        }

        input[type="radio"]:checked {
            background-color: var(--verde);
            /* Color verde cuando está seleccionado */
        }

        input[type="radio"]:checked::before {
            content: '';
            position: absolute;
            width: 5px;
            height: 6px;
            background-color: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Estilos para los labels de los radio buttons */
        label {
            color: black;
            font-size: 1rem;
            cursor: pointer;
            user-select: none;
            margin-right: 1rem;
        }

        /* Mejorar los contenedores de las preguntas */
        [id^="pregunta"] {
            padding: 1rem;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        [id^="pregunta"]:hover {
            background-color: var(--gris-medio);
        }

        /* Estilos para los títulos */
        h3.card-title {
            color: var(--azul-oscuro);
            margin-bottom: 1.5rem;
            font-weight: 900;
        }

        h5.titulo-seccion {
            color: var(--azul-cielo);
            margin-bottom: 1rem;
            font-family: 'Arial', sans-serif;
            /* Puedes cambiarlo por una fuente diferente */
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-bottom: 2px solid var(--verde);
            padding-bottom: 0.3rem;
        }

        /* ESTILO ESPECIFICO PARA LAS H6 FR GUIAS 1 2 Y 3 */

        .text-guia {
            font-size: 0.7rem;
            color: var(--blanco);
            text-transform: uppercase;
            font-weight: 680;
            letter-spacing: 1px;
            padding: 5px 20px;
            background: var(--verde);
            border-radius: 30px;
            margin: 15px auto;
            box-shadow: 0 2px 10px rgba(33, 150, 243, 0.2);
            max-width: fit-content;
            border: 2px solid var(--gris-claro);
        }


        /* Estilos para los íconos de información */
        .fa-info-circle {
            color: #3498db;
            margin-right: 8px;
            cursor: help;
        }


        #datos {
            display: flex;
            flex-direction: column;
            padding: 1.2rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: white;
            position: sticky;
            top: 4px;
        }

        /* Ícono principal grande verde */
        #datos .fa-2x {
            font-size: 2rem !important;
            color: #4CAF50;
            margin-bottom: 0.5rem;
        }

        /* Íconos de encabezado h4 en verde */
        #datos h4 .fa {
            color: #4CAF50;
            font-size: 1.2rem;
            margin-right: 8px;
        }

        /* Íconos pequeños en azul */
        #datos .info-section .fa {
            color: #2196F3;
            margin-right: 8px;
            font-size: 1rem;
        }





        #datos h4 {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            padding-bottom: 0.3rem;
            border-bottom: 2px solid #4CAF50;
        }

        #datos .text-center {
            text-align: center;
            font-size: 0.9rem;
            padding: 0.5rem 0;
            background-color: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        #datos .text-center p {
            margin-top: 0.3rem;
            font-style: italic;
            color: #37474F;
        }

        #datos .info-section span {
            margin-left: 4px;
            font-size: 0.9rem;
            color: #37474F;
        }

        /* Efecto hover en los iconos */
        #datos .fa:hover {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }


        .card-title {
            font-size: 18px;
            text-align: center;
            display: inline-block;
            width: 100%;
            color: #154b75;
        }

        .card {
            width: 100%;
        }

        i {
            color: #154b75;
        }

        .radio-group {
            display: none;
        }

        .radio-label {
            background-color: #99abb4;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .radio-label.selected {
            background-color: var(--verde);
        }


        h5{
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .info-section p {
            font-size: 14px;
            margin: 10px 0;
        }

        .info-section strong {
            font-size: 15px;
            display: flex;
            align-items: center;
        }

        .info-section span {
            display: block;
            margin-top: 5px;
            font-size: 14px;
        }

        .info-section i {
            margin-right: 10px;
            color: #0099c7;
        }


        hr {
            border: 0;
            height: 1px;
            background: #ddd;
            margin: 20px 0;
        }


        /* Estilos Base del Modal */
        .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(26, 26, 26, 0.15);
            background-color: var(--blanco);
        }

        .modal-backdrop.show {
            opacity: 0.85;
        }

        .modal-backdrop {
            background-color: var(--negro);
        }

        /* Encabezado del Modal */
        .modal-header {
            background-color: var(--azul-oscuro);
            color: var(--blanco);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .modal-header .modal-title {
            font-size: 1.25rem;
            font-weight: 500;
            letter-spacing: 0.3px;
            color: var(--blanco);
        }

        .modal-header img {
            max-height: 45px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* Cuerpo del Modal */
        .modal-body {
            padding: 1.75rem 1.5rem;
            color: var(--negro);
            background: linear-gradient(to bottom, var(--blanco), var(--gris-claro));
        }

        .modal-body h6 {
            color: var(--azul-oscuro);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .modal-body p {
            color: var(--negro);
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        /* Pie del Modal */
        .modal-footer {
            padding: 1rem 1.5rem;
            background-color: var(--gris-claro);
            border-top: 1px solid var(--gris-medio);
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        /* Botones */
        .btn-info {
            background-color: var(--azul-cielo);
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            color: var(--blanco);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-info:hover {
            background-color: var(--azul-oscuro);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 155, 207, 0.2);
        }

        .btn-info i {
            color: var(--blanco);
            font-size: 1rem;
        }

        /* Elementos Especiales */
        #instruccionesFoto {
            background-color: var(--gris-claro);
            padding: 1.25rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--azul-cielo);
        }

        /* #loadingSpinner {
            color: var(--azul-oscuro);
            padding: 2rem 0;
        }

        .spinner-border {
            color: var(--azul-cielo);
        } */

        /* Íconos y Alertas */
        .fa-exclamation-triangle {
            margin-right: 0.5rem;
            color: var(--verde);
        }

        .fa-info-circle {
            margin-right: 0.5rem;
            color: var(--azul-cielo);
        }

        .fa-check {
            color: var(--blanco);
        }

        /* Contenedor de Video */
        #video-container {
            border-radius: 6px;
            overflow: hidden;
            margin: 1rem 0;
            border: 2px solid var(--gris-medio);
            box-shadow: 0 4px 12px rgba(26, 26, 26, 0.08);
        }

        /* Avisos Importantes */
        .aviso-importante {
            background-color: var(--gris-claro);
            border-left: 4px solid var(--verde);
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0 6px 6px 0;
        }

        /* Elementos de Formulario */
        #form-foto {
            margin: 0;
            background-color: var(--blanco);
            padding: 1rem;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(26, 26, 26, 0.05);
        }

        /* Estilos de Texto */
        .text-muted {
            color: #6c757d !important;
        }

        .text-info {
            color: var(--azul-cielo) !important;
        }

        /* Efectos Hover */
        .modal-content button:active {
            transform: translateY(1px);
        }

        /* Transiciones */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        .modal.fade.show .modal-dialog {
            transform: none;
        }

        /* Ajustes Responsivos */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-header .modal-title {
                font-size: 1.1rem;
            }

            .modal-body {
                padding: 1.25rem;
            }

            .btn-info {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Accesibilidad */
        .btn-info:focus {
            box-shadow: 0 0 0 3px rgba(0, 155, 207, 0.25);
            outline: none;
        }

        /* Barra de Desplazamiento Personalizada */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: var(--gris-claro);
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: var(--azul-cielo);
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: var(--azul-oscuro);
        }

/* stilo de las notas que estan debajo de algnas preguntas */
        .text-nota {
            background-color: #f0f8ff;
            border-left: 4px solid var(--verde);
            padding: 0.5rem;
            border-radius: 4px;
            color: var(--negro);
            font-weight: 500;
            margin: 1rem 0;
            line-height: 1;
            font-size: 0.9rem;
        }
        
    </style>

    <header class="header-container">
        <div class="header-content">
            <div class="logo-wrapper">
                <img src="/assets/images/Logo_Color_results_original.png" alt="Results In Performance Logo">
            </div>
            <div class="title-wrapper">
                <h1 class="main-title">Results In Performance</h1>
            </div>
            <div class="spacer"></div>
        </div>
    </header>


    <div class="row">
        <div class="col-9 mt-3">
            <div class="card-container">
                <div class="col-12">
                    <input type="hidden" class="form-control" id="ID_RECOPSICORESPUESTAS" name="ID_RECOPSICORESPUESTAS" value="0">
                    <input type="hidden" class="form-control" id="TRABAJADOR_ID" name="TRABAJADOR_ID" value="0">
                </div>
                <div id="guia1" class="card" style="display: block">
                    <h6 style="text-align: center" class="text-guia">Guía de referencia I</h6>
                    <h3 class="card-title"><b>GUÍA PARA IDENTIFICAR A LOS TRABAJADORES QUE FUERON SUJETOS A ACONTECIMIENTOS TRAUMÁTICOS SEVEROS</b></h3>
                    <hr>
                    <form enctype="multipart/form-data" method="post" name="guia_1" id="guia_1">
                        {!! csrf_field() !!}

                        <div id="seccion1" style="padding: 10px;">
                            <div id="titulo1">
                                <h5 style="text-align: left; width: 70%;" class="titulo-seccion"><b>I.- Acontecimiento traumático severo</b></h5>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="GUIAI_ID_RECOPSICORESPUESTAS" name="ID_RECOPSICORESPUESTAS" value="0">
                                <input type="hidden" class="form-control" id="GUIAI_TRABAJADOR_ID" name="TRABAJADOR_ID" value="0">
                            </div>
                            <div id="pregunta1_1" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1; font-style: italic; margin-bottom: 10px;"><i class="fa fa-info-circle" id="Exp1_1" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    1. ¿Ha presenciado o sufrido alguna vez, durante o con motivo del trabajo un acontecimiento como los siguientes?:</p>
                                <div style="display: none; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta1_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta1_si" name="GUIA1_1" value="1" onchange="guia1()">
                                    </div>
                                    <div>
                                        <label for="pregunta1_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta1_no" name="GUIA1_1" value="0" onchange="guia1()">
                                    </div>
                                </div>

                            </div>
                            <div id="pregunta2_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_2" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    2. ¿Accidente que tenga como consecuencia la muerte, la pérdida de un miembro o una lesión grave?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta2_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta2_si" name="GUIA1_2" value="1" onchange="guia1()">
                                    </div>
                                    <div>
                                        <label for="pregunta2_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta2_no" name="GUIA1_2" value="0" onchange="guia1()">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta3_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_3" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    3. ¿Asaltos?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta3_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta3_si" name="GUIA1_3" value="1" onchange="guia1()">
                                    </div>

                                    <div>
                                        <label for="pregunta3_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta3_no" name="GUIA1_3" value="0" onchange="guia1()">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta4_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_4" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    4. ¿Actos violentos que derivaron en lesiones graves?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta4_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta4_si" name="GUIA1_4" value="1" onchange="guia1()">
                                    </div>

                                    <div>
                                        <label for="pregunta4_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta4_no" name="GUIA1_4" value="0" onchange="guia1()">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta5_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_5" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    5. ¿Secuestro?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta5_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta5_si" name="GUIA1_5" value="1" onchange="guia1()">
                                    </div>

                                    <div>
                                        <label for="pregunta5_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta5_no" name="GUIA1_5" value="0" onchange="guia1()">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta6_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_6" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    6. ¿Amenazas?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta6_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta6_si" name="GUIA1_6" value="1" onchange="guia1()">
                                    </div>

                                    <div>
                                        <label for="pregunta6_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta6_no" name="GUIA1_6" value="0" onchange="guia1()">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta7_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_7" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    7. ¿Cualquier otro que ponga en riesgo su vida o salud, y/o la de otras personas?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta7_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta7_si" name="GUIA1_7" value="1" onchange="guia1()">
                                    </div>
                                    <div>
                                        <label for="pregunta7_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta7_no" name="GUIA1_7" value="0" onchange="guia1()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="seccion2" class="mt-3" style="display: none; padding: 10px;">
                            <div id="titulo2">
                                <h5 style="text-align: left; width: 70%;" class="titulo-seccion"><b>II.- Recuerdos persistentes sobre el acontecimiento (durante el último mes):</b></h5>
                            </div>
                            <div id="pregunta8_1" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_8" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    8. ¿Ha tenido recuerdos recurrentes sobre el acontecimiento que le provocan malestares?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta8_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="preguta8_si" name="GUIA1_8" value="1">
                                    </div>

                                    <div>
                                        <label for="preguta8_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="preguta8_no" name="GUIA1_8" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta9_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_9" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    9. ¿Ha tenido sueños de carácter recurrente sobre el acontecimiento, que le producen malestar?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta9_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta9_si" name="GUIA1_9" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta9_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta9_no" name="GUIA1_9" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="seccion3" class="mt-3" style="display: none; padding: 10px;">
                            <div id="titulo3">
                                <h5 style="text-align: left; width: 70%;" class="titulo-seccion"><b>III.- Esfuerzo por evitar circunstancias parecidas o asociadas al acontecimiento (durante el último mes):</b></h5>
                            </div>
                            <div id="pregunta10_1" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_10" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    10. ¿Se ha esforzado por evitar todo tipo de sentimientos, conversaciones o situaciones que le puedan recordar el acontecimiento?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta10_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="preguta10_si" name="GUIA1_10" value="1">
                                    </div>

                                    <div>
                                        <label for="preguta10_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="preguta10_no" name="GUIA1_10" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta11_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_11" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    11. ¿Se ha esforzado por evitar todo tipo de actividades, lugares o personas que motivan recuerdos del acontecimiento?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta11_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta11_si" name="GUIA1_11" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta11_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta11_no" name="GUIA1_11" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta12_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_12" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    12. ¿Ha tenido dificultad para recordar alguna parte importante del evento?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta12_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta12_si" name="GUIA1_12" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta12_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta12_no" name="GUIA1_12" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta13_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_13" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    13. ¿Ha disminuido su interés en sus actividades cotidianas?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta13_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta13_si" name="GUIA1_13" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta13_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta13_no" name="GUIA1_13" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta14_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_14" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    14. ¿Se ha sentido usted alejado o distante de los demás?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta14_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta14_si" name="GUIA1_14" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta14_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta14_no" name="GUIA1_14" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta15_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_15" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    15. ¿Ha notado que tiene dificultad para expresar sus sentimientos?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta15_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta15_si" name="GUIA1_15" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta15_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta15_no" name="GUIA1_15" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta16_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_16" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    16. ¿Ha tenido la impresión de que su vida se va a acortar, que va a morir antes que otras personas o que tiene un futuro limitado?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta16_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta16_si" name="GUIA1_16" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta16_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta16_no" name="GUIA1_16" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="seccion4" class="mt-3" style="display: none; padding: 10px; ">
                            <div id="titulo4">
                                <h5 style="text-align: left; width: 70%;" class="titulo-seccion"><b>IV.- Afectación (durante el último mes):</b></h5>
                            </div>
                            <div id="pregunta17_1" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_17" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    17. ¿Ha tenido usted dificultades para dormir?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta17_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="preguta17_si" name="GUIA1_17" value="1">
                                    </div>

                                    <div>
                                        <label for="preguta17_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="preguta17_no" name="GUIA1_17" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta18_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_18" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    18. ¿Ha estado particularmente irritable o le han dado arranques de coraje?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta18_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta18_si" name="GUIA1_18" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta18_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta18_no" name="GUIA1_18" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta19_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_19" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    19. ¿Ha tenido dificultad para concentrarse?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta19_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta19_si" name="GUIA1_19" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta19_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta19_no" name="GUIA1_19" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta20_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_20" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    20. ¿Ha estado nervioso o constantemente en alerta?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta20_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta20_si" name="GUIA1_20" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta20_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta20_no" name="GUIA1_20" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta21_1" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp1_21" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    21. ¿Se ha sobresaltado fácilmente por cualquier cosa?</p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="pregunta21_si" style="margin-right: 5px;">Sí</label>
                                        <input type="radio" id="pregunta21_si" name="GUIA1_21" value="1">
                                    </div>

                                    <div>
                                        <label for="pregunta21_no" style="margin-right: 5px;">No</label>
                                        <input type="radio" id="pregunta21_no" name="GUIA1_21" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

                <div id="guia2" class="card mt-4" style="display: block">
                    <h6 style="text-align: center" class="text-guia" class="text-guia">Guía de referencia II</h6>
                    <h3 class="card-title"><b>GUÍA PARA IDENTIFICAR LOS FACTORES DE RIESGO PSICOSOCIAL EN LOS CENTROS DE TRABAJO</b></h3>
                    <hr>
                    <form enctype="multipart/form-data" method="post" name="guia_2" id="guia_2">
                        {!! csrf_field() !!}

                        <div class="col-12">
                            <input type="hidden" class="form-control" id="GUIAII_ID_RECOPSICORESPUESTAS" name="ID_RECOPSICORESPUESTAS" value="0">
                            <input type="hidden" class="form-control" id="GUIAII_TRABAJADOR_ID" name="TRABAJADOR_ID" value="0">
                        </div>
                        <div id="seccion1_2" class="mt-3" style="display: block; padding: 10px;">

                            <div id="pregunta1_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;">
                                    <i class="fa fa-info-circle" id="Exp2_1" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi trabajo me exige hacer mucho esfuerzo <br> físico
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta1_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta1_siempre" name="GUIA2_1" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta1_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta1_casi" name="GUIA2_1" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta1_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta1_algunas" name="GUIA2_1" value="2" ">
                                    </div>
                                    <div>
                                        <label for=" preguta1_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta1_casinunca" name="GUIA2_1" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta1_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta1_nunca" name="GUIA2_1" value="0">
                                    </div>
                                </div>
                            </div>


                            <div id="pregunta2_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_2" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Me preocupa sufrir un accidente en mi
                                    trabajo </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta2_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta2_siempre" name="GUIA2_2" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta2_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta2_casi" name="GUIA2_2" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta2_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta2_algunas" name="GUIA2_2" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta2_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta2_casinunca" name="GUIA2_2" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta2_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta2_nunca" name="GUIA2_2" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta3_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_3" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Considero que las actividades que <br> realizo
                                    son peligrosas
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta3_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta3_siempre" name="GUIA2_3" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta3_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta3_casi" name="GUIA2_3" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta3_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta3_algunas" name="GUIA2_3" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta3_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta3_casinunca" name="GUIA2_3" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta3_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta3_nunca" name="GUIA2_3" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta4_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_4" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Por la cantidad de trabajo que tengo debo quedarme tiempo adicional a mi turno
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta4_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta4_siempre" name="GUIA2_4" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta4_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta4_casi" name="GUIA2_4" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta4_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta4_algunas" name="GUIA2_4" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta4_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta4_casinunca" name="GUIA2_4" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta4_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta4_nunca" name="GUIA2_4" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta5_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_5" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Por la cantidad de trabajo que tengo debo trabajar sin parar
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta5_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta5_siempre" name="GUIA2_5" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta5_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta5_casi" name="GUIA2_5" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta5_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta5_algunas" name="GUIA2_5" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta5_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta5_casinunca" name="GUIA2_5" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta5_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta5_nunca" name="GUIA2_5" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta6_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_6" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Considero que es necesario mantener un <br> ritmo de trabajo acelerado
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta6_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta6_siempre" name="GUIA2_6" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta6_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta6_casi" name="GUIA2_6" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta6_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta6_algunas" name="GUIA2_6" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta6_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta6_casinunca" name="GUIA2_6" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta6_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta6_nunca" name="GUIA2_6" value="0">
                                    </div>
                                </div>
                            </div>


                            <div id="pregunta7_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_7" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi trabajo exige que esté muy concentrado
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta7_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta7_siempre" name="GUIA2_7" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta7_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta7_casi" name="GUIA2_7" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta7_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta7_algunas" name="GUIA2_7" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta7_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta7_casinunca" name="GUIA2_7" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta7_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta7_nunca" name="GUIA2_7" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta8_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_8" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi trabajo requiere que memorice mucha información
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta8_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta8_siempre" name="GUIA2_8" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta8_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta8_casi" name="GUIA2_8" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta8_algunas" class="radio-label" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta8_algunas" name="GUIA2_8" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta8_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta8_casinunca" name="GUIA2_8" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta8_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta8_nunca" name="GUIA2_8" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta9_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_9" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi trabajo exige que atienda varios asuntos <br>
                                    al mismo tiempo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta9_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta9_siempre" name="GUIA2_9" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta9_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta9_casi" name="GUIA2_9" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta9_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta9_algunas" name="GUIA2_9" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta9_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta9_casinunca" name="GUIA2_9" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta9_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta9_nunca" name="GUIA2_9" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <h6>Las preguntas siguientes están relacionadas con las actividades que realiza en su trabajo y las responsabilidades que tiene.</h6>
                            </div>
                            <div id="pregunta10_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_10" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    En mi trabajo soy responsable de cosas de mucho valor
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta10_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta10_siempre" name="GUIA2_10" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta10_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta10_casi" name="GUIA2_10" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta10_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta10_algunas" name="GUIA2_10" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta10_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta10_casinunca" name="GUIA2_10" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta10_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta10_nunca" name="GUIA2_10" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta11_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_11" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Respondo ante mi jefe por los resultados <br>de toda mi área de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta11_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta11_siempre" name="GUIA2_11" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta11_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta11_casi" name="GUIA2_11" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta11_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta11_algunas" name="GUIA2_11" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta11_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta11_casinunca" name="GUIA2_11" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta11_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta11_nunca" name="GUIA2_11" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta12_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_12" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    En mi trabajo me dan órdenes contradictorias
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta12_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta12_siempre" name="GUIA2_12" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta12_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta12_casi" name="GUIA2_12" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta12_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta12_algunas" name="GUIA2_12" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta12_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta12_casinunca" name="GUIA2_12" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta12_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta12_nunca" name="GUIA2_12" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta13_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_13" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Considero que en mi trabajo me piden <br> hacer cosas innecesarias
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta13_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta13_siempre" name="GUIA2_13" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta13_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta13_casi" name="GUIA2_13" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta13_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta13_algunas" name="GUIA2_13" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta13_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta13_casinunca" name="GUIA2_13" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta13_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta13_nunca" name="GUIA2_13" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h6>Las preguntas siguientes están relacionadas con el tiempo destinado a su trabajo y sus responsabilidades familiares.</h6>
                            </div>
                            <div id="pregunta14_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_14" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Trabajo horas extras más de tres veces <br> a la semana
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta14_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta14_siempre" name="GUIA2_14" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta14_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta14_casi" name="GUIA2_14" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta14_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta14_algunas" name="GUIA2_14" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta14_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta14_casinunca" name="GUIA2_14" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta14_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta14_nunca" name="GUIA2_14" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta15_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_15" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi trabajo me exige laborar en días de <br> descanso, festivos o fines de semana
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta15_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta15_siempre" name="GUIA2_15" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta15_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta15_casi" name="GUIA2_15" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta15_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta15_algunas" name="GUIA2_15" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta15_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta15_casinunca" name="GUIA2_15" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta15_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta15_nunca" name="GUIA2_15" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta16_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_16" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Considero que el tiempo en el trabajo <br>
                                    es mucho y perjudica mis actividades familiares o personales
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta16_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta16_siempre" name="GUIA2_16" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta16_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta16_casi" name="GUIA2_16" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta16_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta16_algunas" name="GUIA2_16" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta16_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta16_casinunca" name="GUIA2_16" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta16_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta16_nunca" name="GUIA2_16" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta17_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_17" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Pienso en las actividades familiares o <br> personales cuando estoy en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta17_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta17_siempre" name="GUIA2_17" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta17_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta17_casi" name="GUIA2_17" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta17_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta17_algunas" name="GUIA2_17" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta17_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta17_casinunca" name="GUIA2_17" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta17_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta17_nunca" name="GUIA2_17" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h6>Las preguntas siguientes están relacionadas con las decisiones que puede tomar en su trabajo.
                                </h6>
                            </div>

                            <div id="pregunta18_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_18" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi trabajo permite que desarrolle nuevas habilidades
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta18_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta18_siempre" name="GUIA2_18" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta18_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta18_casi" name="GUIA2_18" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta18_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta18_algunas" name="GUIA2_18" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta18_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta18_casinunca" name="GUIA2_18" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta18_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta18_nunca" name="GUIA2_18" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta19_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_19" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    En mi trabajo puedo aspirar a un mejor <br>
                                    puesto
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta19_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta19_siempre" name="GUIA2_19" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta19_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta19_casi" name="GUIA2_19" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta19_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta19_algunas" name="GUIA2_19" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta19_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta19_casinunca" name="GUIA2_19" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta19_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta19_nunca" name="GUIA2_19" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta20_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_20" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Durante mi jornada de trabajo puedo <br> tomar pausas cuando las necesito
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta20_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta20_siempre" name="GUIA2_20" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta20_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta20_casi" name="GUIA2_20" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta20_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta20_algunas" name="GUIA2_20" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta20_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta20_casinunca" name="GUIA2_20" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta20_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta20_nunca" name="GUIA2_20" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta21_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_21" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Puedo decidir la velocidad a la que realizo mis actividades en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta21_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta21_siempre" name="GUIA2_21" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta21_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta21_casi" name="GUIA2_21" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta21_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta21_algunas" name="GUIA2_21" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta21_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta21_casinunca" name="GUIA2_21" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta21_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta21_nunca" name="GUIA2_21" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta22_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_22" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Puedo cambiar el orden de las actividades <br> que realizo en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta22_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta22_siempre" name="GUIA2_22" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta22_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta22_casi" name="GUIA2_22" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta22_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta22_algunas" name="GUIA2_22" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta22_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta22_casinunca" name="GUIA2_22" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta22_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta22_nunca" name="GUIA2_22" value="4">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <h6>Las preguntas siguientes están relacionadas con la capacitación e información que recibe sobre su trabajo.
                                </h6>
                            </div>


                            <div id="pregunta23_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_23" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Me informan con claridad cuáles son mis funciones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta23_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta23_siempre" name="GUIA2_23" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta23_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta23_casi" name="GUIA2_23" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta23_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta23_algunas" name="GUIA2_23" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta23_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta23_casinunca" name="GUIA2_23" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta23_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta23_nunca" name="GUIA2_23" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta24_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_24" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Me explican claramente los resultados <br> que debo obtener en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta24_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta24_siempre" name="GUIA2_24" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta24_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta24_casi" name="GUIA2_24" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta24_algunas" class="radio-label" class="radio-label" z style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta24_algunas" name="GUIA2_24" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta24_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta24_casinunca" name="GUIA2_24" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta24_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta24_nunca" name="GUIA2_24" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta25_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_25" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Me informan con quién puedo resolver problemas o asuntos de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta25_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta25_siempre" name="GUIA2_25" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta25_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta25_casi" name="GUIA2_25" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta25_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta25_algunas" name="GUIA2_25" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta25_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta25_casinunca" name="GUIA2_25" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta25_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta25_nunca" name="GUIA2_25" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta26_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_26" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Me permiten asistir a capacitaciones relacionadas con mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta26_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta26_siempre" name="GUIA2_26" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta26_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta26_casi" name="GUIA2_26" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta26_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta26_algunas" name="GUIA2_26" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta26_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta26_casinunca" name="GUIA2_26" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta26_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta26_nunca" name="GUIA2_26" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta27_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_27" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Recibo capacitación útil para hacer mi <br>
                                    trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta27_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta27_siempre" name="GUIA2_27" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta27_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta27_casi" name="GUIA2_27" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta27_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta27_algunas" name="GUIA2_27" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta27_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta27_casinunca" name="GUIA2_27" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta27_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta27_nunca" name="GUIA2_27" value="4">
                                    </div>
                                </div>
                            </div>


                            <div class="mt-5">
                                <h6>Las preguntas siguientes se refieren a las relaciones con sus compañeros de trabajo y su jefe.
                                </h6>
                            </div>

                            <div id="pregunta28_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_28" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi jefe tiene en cuenta mis puntos de vista <br> y opiniones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta28_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta28_siempre" name="GUIA2_28" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta28_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta28_casi" name="GUIA2_28" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta28_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta28_algunas" name="GUIA2_28" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta28_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta28_casinunca" name="GUIA2_28" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta28_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta28_nunca" name="GUIA2_28" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta29_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_29" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi jefe ayuda a solucionar los problemas <br> que se presentan en el trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta29_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta29_siempre" name="GUIA2_29" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta29_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta29_casi" name="GUIA2_29" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta29_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta29_algunas" name="GUIA2_29" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta29_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta29_casinunca" name="GUIA2_29" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta29_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta29_nunca" name="GUIA2_29" value="4">
                                    </div>
                                </div>
                            </div>


                            <div id="pregunta30_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_30" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Puedo confiar en mis compañeros de <br>trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta30_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta30_siempre" name="GUIA2_30" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta30_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta30_casi" name="GUIA2_30" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta30_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta30_algunas" name="GUIA2_30" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta30_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta30_casinunca" name="GUIA2_30" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta30_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta30_nunca" name="GUIA2_30" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta31_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_31" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Cuando tenemos que realizar trabajo de <br> equipo los compañeros colaboran
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta31_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta31_siempre" name="GUIA2_31" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta31_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta31_casi" name="GUIA2_31" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta31_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta31_algunas" name="GUIA2_31" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta31_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta31_casinunca" name="GUIA2_31" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta31_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta31_nunca" name="GUIA2_31" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta32_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_32" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mis compañeros de trabajo me ayudan <br> cuando tengo dificultades
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta32_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta32_siempre" name="GUIA2_32" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta32_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta32_casi" name="GUIA2_32" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta32_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta32_algunas" name="GUIA2_32" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta32_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta32_casinunca" name="GUIA2_32" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta32_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta32_nunca" name="GUIA2_32" value="4">
                                    </div>
                                </div>
                            </div>


                            <div id="pregunta33_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_33" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    En mi trabajo puedo expresarme <br> libremente sin interrupciones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta33_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta33_siempre" name="GUIA2_33" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta33_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta33_casi" name="GUIA2_33" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta33_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta33_algunas" name="GUIA2_33" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta33_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta33_casinunca" name="GUIA2_33" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta33_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta33_nunca" name="GUIA2_33" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta34_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_34" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Recibo críticas constantes a mi <br> persona y/o trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta34_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta34_siempre" name="GUIA2_34" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta34_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta34_casi" name="GUIA2_34" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta34_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta34_algunas" name="GUIA2_34" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta34_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta34_casinunca" name="GUIA2_34" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta34_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta34_nunca" name="GUIA2_34" value="0">
                                    </div>
                                </div>
                            </div>


                            <div id="pregunta35_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_35" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Recibo burlas, calumnias, difamaciones, humillaciones o ridiculizaciones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta35_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta35_siempre" name="GUIA2_35" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta35_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta35_casi" name="GUIA2_35" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta35_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta35_algunas" name="GUIA2_35" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta35_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta35_casinunca" name="GUIA2_35" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta35_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta35_nunca" name="GUIA2_35" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta36_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_36" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Se ignora mi presencia o se me excluye <br> de las reuniones de trabajo y en la toma <br>de decisiones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta36_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta36_siempre" name="GUIA2_36" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta36_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta36_casi" name="GUIA2_36" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta36_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta36_algunas" name="GUIA2_36" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta36_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta36_casinunca" name="GUIA2_36" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta36_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta36_nunca" name="GUIA2_36" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta37_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_37" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Se manipulan las situaciones de trabajo <br> para hacerme parecer un mal trabajador
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta37_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta37_siempre" name="GUIA2_37" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta37_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta37_casi" name="GUIA2_37" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta37_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta37_algunas" name="GUIA2_37" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta37_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta37_casinunca" name="GUIA2_37" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta37_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta37_nunca" name="GUIA2_37" value="0">
                                    </div>
                                </div>
                            </div>


                            <div id="pregunta38_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_38" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Se ignoran mis éxitos laborales y se <br> atribuyen a otros trabajadores
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta38_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta38_siempre" name="GUIA2_38" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta38_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta38_casi" name="GUIA2_38" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta38_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta38_algunas" name="GUIA2_38" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta38_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta38_casinunca" name="GUIA2_38" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta38_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta38_nunca" name="GUIA2_38" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta39_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_39" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Me bloquean o impiden las oportunidades <br> que tengo para obtener ascenso o mejora <br>en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta39_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta39_siempre" name="GUIA2_39" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta39_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta39_casi" name="GUIA2_39" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta39_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta39_algunas" name="GUIA2_39" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta39_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta39_casinunca" name="GUIA2_39" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta39_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta39_nunca" name="GUIA2_39" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta40_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_40" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    He presenciado actos de violencias en mi <br> centro de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta40_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta40_siempre" name="GUIA2_40" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta40_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta40_casi" name="GUIA2_40" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta40_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta40_algunas" name="GUIA2_40" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta40_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta40_casinunca" name="GUIA2_40" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta40_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta40_nunca" name="GUIA2_40" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h6>Las preguntas siguientes están relacionadas con la atención a clientes y usuarios.
                                </h6>
                            </div>

                            <div id="pregunta47_2" class="mt-5" style="display: flex; align-items: center; margin-bottom: 10px;">
                                <p style="margin: 0; white-space: nowrap; margin-right: 10px;"><i class="fa fa-info-circle" id="Exp2_1ADI" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    En mi trabajo debo brindar servicio a clientes o usuarios:
                                </p>
                                <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi1_si" style="line-height: 1; margin-right: 5px;">Sí</label>
                                        <input type="radio" id="preguntaadi1_si" name="GUIA2_47" value="1" style="vertical-align: middle;" onchange="clientesyusuarios()">
                                    </div>
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi1_no" style="line-height: 1; margin-right: 5px;">No</label>
                                        <input type="radio" id="preguntaadi1_no" name="GUIA2_47" value="0" style="vertical-align: middle;" onchange="clientesyusuarios()">
                                    </div>
                                </div>
                            </div>


                            <div class="mt-3">
                                <h6 class="text-nota">Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO" pase a las preguntas de la sección siguiente.
                                </h6>
                            </div>

                        </div>


                        <div id="seccion2_2" class="mt-2" style="display: none; padding: 10px;">

                            <div id="pregunta41_2" class="mt-3" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_41" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Atiendo clientes o usuarios muy enojados
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta41_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta41_siempre" name="GUIA2_41" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta41_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta41_casi" name="GUIA2_41" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta41_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta41_algunas" name="GUIA2_41" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta41_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta41_casinunca" name="GUIA2_41" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta41_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta41_nunca" name="GUIA2_41" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta42_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_42" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Mi trabajo me exige atender personas muy necesitadas de ayuda o enfermas
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta42_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta42_siempre" name="GUIA2_42" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta42_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta42_casi" name="GUIA2_42" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta42_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta42_algunas" name="GUIA2_42" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta42_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta42_casinunca" name="GUIA2_42" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta42_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta42_nunca" name="GUIA2_42" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta43_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_43" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Para hacer mi trabajo debo demostrar sentimientos distintos a los míos
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta43_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta43_siempre" name="GUIA2_43" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta43_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta43_casi" name="GUIA2_43" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta43_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta43_algunas" name="GUIA2_43" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta43_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta43_casinunca" name="GUIA2_43" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta43_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta43_nunca" name="GUIA2_43" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="seccion3_2" class="mt-2" style="display: block; padding: 10px;">
                            <div id="pregunta48_2" class="mt-2" style="display: flex; align-items: center; margin-bottom: 10px;">
                                <p style="margin: 0; white-space: nowrap; margin-right: 10px;"><i class="fa fa-info-circle" id="Exp2_2ADI" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Soy jefe de otros trabajadores:
                                </p>
                                <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi2_si" style="line-height: 1; margin-right: 5px;">Sí</label>
                                        <input type="radio" id="preguntaadi2_si" name="GUIA2_48" value="1" style="vertical-align: middle;" onchange="jefetrabajadores()">
                                    </div>
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi2_no" style="line-height: 1; margin-right: 5px;">No</label>
                                        <input type="radio" id="preguntaadi2_no" name="GUIA2_48" value="0" style="vertical-align: middle;" onchange="jefetrabajadores()">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6 class="text-nota">Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO", ha concluido el cuestionario.
                                </h6>
                            </div>
                        </div>
                        <div id="seccion4_2" class="mt-2" style="display: none; padding: 10px;">
                            <div class="mt-3">
                                <h6>Las siguientes preguntas están relacionadas con las actitudes de los trabajadores que supervisa.
                                </h6>
                            </div>
                            <div id="pregunta44_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_44" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Comunican tarde los asuntos de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta44_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta44_siempre" name="GUIA2_44" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta44_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta44_casi" name="GUIA2_44" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta44_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta44_algunas" name="GUIA2_44" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta44_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta44_casinunca" name="GUIA2_44" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta44_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta44_nunca" name="GUIA2_44" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta45_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_45" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Dificultan el logro de los resultados del <br>
                                    trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta45_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta45_siempre" name="GUIA2_45" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta45_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta45_casi" name="GUIA2_45" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta45_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta45_algunas" name="GUIA2_45" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta45_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta45_casinunca" name="GUIA2_45" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta45_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta45_nunca" name="GUIA2_45" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta46_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp2_46" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Ignoran las sugerencias para mejorar <br> su trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta46_siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta46_siempre" name="GUIA2_46" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta46_casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta46_casi" name="GUIA2_46" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta46_algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta46_algunas" name="GUIA2_46" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta46_casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta46_casinunca" name="GUIA2_46" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta46_nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta46_nunca" name="GUIA2_46" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger" id="guardar_guia2" onclick="submitGuia1y2()">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </form>
                </div>

                <div id="guia3" class="card mt-4" style="display: block">
                    <h6 style="text-align: center" class="text-guia">Guía de referencia III</h6>
                    <h3 class="card-title"><b>GUÍA PARA IDENTIFICAR LOS FACTORES DE RIESGO PSICOSOCIAL Y EVALUAR EL ENTORNO ORGANIZACIONAL EN LOS CENTROS DE TRABAJO</b></h3>
                    <hr>
                    <form enctype="multipart/form-data" method="post" name="guia_3" id="guia_3">
                        {!! csrf_field() !!}

                        <div class="col-12">
                            <input type="hidden" class="form-control" id="GUIAIII_ID_RECOPSICORESPUESTAS" name="ID_RECOPSICORESPUESTAS" value="0">
                            <input type="hidden" class="form-control" id="GUIAIII_TRABAJADOR_ID" name="TRABAJADOR_ID" value="0">
                        </div>
                        <div class="mt-3">
                            <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                Para responder las siguientes preguntas considere las condiciones ambientales de su centro de trabajo.</p>
                        </div>
                        <div id="seccion1_3" style="display: block;">
                            <div id="pregunta1_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_1" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    1. El espacio donde trabajo me permite <br> realizar mis actividades de manera segura <br> e higiénica
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta1_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta1_3siempre" name="GUIA3_1" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta1_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta1_3casi" name="GUIA3_1" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta1_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta1_3algunas" name="GUIA3_1" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta1_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta1_3casinunca" name="GUIA3_1" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta1_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta1_3nunca" name="GUIA3_1" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta2_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_2" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    2. Mi trabajo me exige hacer muchos <br> esfuerzo físico
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta2_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta2_3siempre" name="GUIA3_2" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta2_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta2_3casi" name="GUIA3_2" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta2_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta2_3algunas" name="GUIA3_2" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta2_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta2_3casinunca" name="GUIA3_2" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta2_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta2_3nunca" name="GUIA3_2" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta3_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_3" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    3. Me preocupa sufrir un accidente en mi <br> trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta3_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta3_3siempre" name="GUIA3_3" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta3_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta3_3casi" name="GUIA3_3" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta3_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta3_3algunas" name="GUIA3_3" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta3_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta3_3casinunca" name="GUIA3_3" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta3_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta3_3nunca" name="GUIA3_3" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta4_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_4" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    4. Considero que en mi trabajo se aplican las normas de seguridad y salud en el trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta4_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta4_3siempre" name="GUIA3_4" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta4_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta4_3casi" name="GUIA3_4" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta4_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta4_3algunas" name="GUIA3_4" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta4_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta4_3casinunca" name="GUIA3_4" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta4_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta4_3nunca" name="GUIA3_4" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta5_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_5" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    5. Considero que las actividades que realizo <br> son peligrosas
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta5_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta5_3siempre" name="GUIA3_5" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta5_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta5_3casi" name="GUIA3_5" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta5_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta5_3algunas" name="GUIA3_5" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta5_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta5_3casinunca" name="GUIA3_5" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta5_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta5_3nunca" name="GUIA3_5" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Para responder a las preguntas siguientes piense en la cantidad y ritmo de trabajo que tiene.</p>
                            </div>
                            <div id="pregunta6_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_6" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    6. Por la cantidad de trabajo que tengo debo quedarme tiempo adicional a mi turno
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta6_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta6_3siempre" name="GUIA3_6" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta6_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta6_3casi" name="GUIA3_6" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta6_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta6_3algunas" name="GUIA3_6" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta6_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta6_3casinunca" name="GUIA3_6" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta6_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta6_3nunca" name="GUIA3_6" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta7_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_7" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    7. Por la cantidad de trabajo que tengo debo trabajar sin parar
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta7_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta7_3siempre" name="GUIA3_7" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta7_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta7_3casi" name="GUIA3_7" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta7_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta7_3algunas" name="GUIA3_7" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta7_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta7_3casinunca" name="GUIA3_7" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta7_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta7_3nunca" name="GUIA3_7" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta8_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_8" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    8. Considero que es necesario mantener un <br> ritmo de trabajo acelerado
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta8_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta8_3siempre" name="GUIA3_8" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta8_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta8_3casi" name="GUIA3_8" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta8_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta8_3algunas" name="GUIA3_8" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta8_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta8_3casinunca" name="GUIA3_8" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta8_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta8_3nunca" name="GUIA3_8" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con el esfuerzo mental que le exige su trabajo.</p>
                            </div>
                            <div id="pregunta9_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_9" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    9. Mi trabajo exige que esté muy concentrado
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta9_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta9_3siempre" name="GUIA3_9" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta9_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta9_3casi" name="GUIA3_9" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta9_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta9_3algunas" name="GUIA3_9" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta9_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta9_3casinunca" name="GUIA3_9" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta9_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta9_3nunca" name="GUIA3_9" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta10_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_10" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    10. Mi trabajo requiere que memorice mucha información
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta10_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta10_3siempre" name="GUIA3_10" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta10_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta10_3casi" name="GUIA3_10" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta10_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta10_3algunas" name="GUIA3_10" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta10_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta10_3casinunca" name="GUIA3_10" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta10_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta10_3nunca" name="GUIA3_10" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta11_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_11" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    11. En mi trabajo tengo que tomar decisiones difíciles muy rápido
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta11_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta11_3siempre" name="GUIA3_11" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta11_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta11_3casi" name="GUIA3_11" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta11_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta11_3algunas" name="GUIA3_11" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta11_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta11_3casinunca" name="GUIA3_11" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta11_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta11_3nunca" name="GUIA3_11" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta12_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_12" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    12. Mi trabajo exige que atienda varios <br> asuntos
                                    al mismo tiempo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta12_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta12_3siempre" name="GUIA3_12" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta12_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta12_3casi" name="GUIA3_12" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta12_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta12_3algunas" name="GUIA3_12" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta12_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta12_3casinunca" name="GUIA3_12" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta12_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta12_3nunca" name="GUIA3_12" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con las actividades que realiza en su trabajo y las responsabilidades que tiene.</p>
                            </div>
                            <div id="pregunta13_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_13" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    13. En mi trabajo soy responsable de cosas de mucho valor
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta13_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta13_3siempre" name="GUIA3_13" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta13_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta13_3casi" name="GUIA3_13" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta13_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta13_3algunas" name="GUIA3_13" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta13_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta13_3casinunca" name="GUIA3_13" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta13_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta13_3nunca" name="GUIA3_13" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta14_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_14" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    14. Respondo ante mi jefe por los resultados <br> de toda mi área de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta14_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta14_3siempre" name="GUIA3_14" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta14_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta14_3casi" name="GUIA3_14" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta14_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta14_3algunas" name="GUIA3_14" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta14_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta14_3casinunca" name="GUIA3_14" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta14_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta14_3nunca" name="GUIA3_14" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta15_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_15" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    15. En el trabajo me dan órdenes <br>contradictorias
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta15_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta15_3siempre" name="GUIA3_15" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta15_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta15_3casi" name="GUIA3_15" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta15_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta15_3algunas" name="GUIA3_15" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta15_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta15_3casinunca" name="GUIA3_15" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta15_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta15_3nunca" name="GUIA3_15" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta16_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_16" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    16. Considero que en mi trabajo me piden<br> hacer cosas innecesarias
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta16_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta16_3siempre" name="GUIA3_16" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta16_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta16_3casi" name="GUIA3_16" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta16_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta16_3algunas" name="GUIA3_16" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta16_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta16_3casinunca" name="GUIA3_16" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta16_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta16_3nunca" name="GUIA3_16" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <h6>Las preguntas siguientes están relacionadas con su jornada de trabajo.
                                </h6>
                            </div>
                            <div id="pregunta17_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_17" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    17. Trabajo horas extras más de tres veces a<br> la semana
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta17_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta17_3siempre" name="GUIA3_17" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta17_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta17_3casi" name="GUIA3_17" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta17_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta17_3algunas" name="GUIA3_17" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta17_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta17_3casinunca" name="GUIA3_17" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta17_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta17_3nunca" name="GUIA3_17" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta18_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_18" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    18. Mi trabajo me exige laborar en días de <br> descanso, festivos o fines de semana
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta18_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta18_3siempre" name="GUIA3_18" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta18_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta18_3casi" name="GUIA3_18" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta18_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta18_3algunas" name="GUIA3_18" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta18_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta18_3casinunca" name="GUIA3_18" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta18_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta18_3nunca" name="GUIA3_18" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta19_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_19" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    19. Considero que el tiempo en el trabajo es <br> mucho y perjudica mis actividades familiares <br> o personales
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta19_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta19_3siempre" name="GUIA3_19" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta19_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta19_3casi" name="GUIA3_19" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta19_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta19_3algunas" name="GUIA3_19" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta19_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta19_3casinunca" name="GUIA3_19" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta19_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta19_3nunca" name="GUIA3_19" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta20_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_20" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    20. Debo atender asuntos de trabajo cuando <br> estoy en casa
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta20_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta20_3siempre" name="GUIA3_20" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta20_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta20_3casi" name="GUIA3_20" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta20_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta20_3algunas" name="GUIA3_20" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta20_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta20_3casinunca" name="GUIA3_20" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta20_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta20_3nunca" name="GUIA3_20" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta21_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_21" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    21. Pienso en las actividades familiares o <br> personales cuando estoy en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta21_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta21_3siempre" name="GUIA3_21" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta21_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta21_3casi" name="GUIA3_21" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta21_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta21_3algunas" name="GUIA3_21" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta21_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta21_3casinunca" name="GUIA3_21" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta21_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta21_3nunca" name="GUIA3_21" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta22_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_22" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    22. Pienso que mis responsabilidades<br>familiares afectan mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta22_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta22_3siempre" name="GUIA3_22" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta22_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta22_3casi" name="GUIA3_22" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta22_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta22_3algunas" name="GUIA3_22" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta22_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta22_3casinunca" name="GUIA3_22" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta22_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta22_3nunca" name="GUIA3_22" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con las decisiones que puede tomar en su trabajo.</p>
                            </div>
                            <div id="pregunta23_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_23" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    23. Mi trabajo permite que desarrolle nuevas habilidades
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta23_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta23_3siempre" name="GUIA3_23" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta23_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta23_3casi" name="GUIA3_23" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta23_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta23_3algunas" name="GUIA3_23" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta23_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta23_3casinunca" name="GUIA3_23" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta23_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta23_3nunca" name="GUIA3_23" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta24_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_24" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    24. En mi trabajo puedo aspirar a un mejor <br>
                                    puesto
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta24_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta24_3siempre" name="GUIA3_24" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta24_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta24_3casi" name="GUIA3_24" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta24_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta24_3algunas" name="GUIA3_24" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta24_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta24_3casinunca" name="GUIA3_24" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta24_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta24_3nunca" name="GUIA3_24" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta25_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_25" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    25. Durante mi jornada de trabajo puedo<br> tomar pausas cuando las necesito
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta25_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta25_3siempre" name="GUIA3_25" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta25_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta25_3casi" name="GUIA3_25" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta25_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta25_3algunas" name="GUIA3_25" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta25_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta25_3casinunca" name="GUIA3_25" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta25_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta25_3nunca" name="GUIA3_25" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta26_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_26" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    26. Puedo decidir cuánto trabajo realizo <br> durante la jornada laboral
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta26_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta26_3siempre" name="GUIA3_26" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta26_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta26_3casi" name="GUIA3_26" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta26_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta26_3algunas" name="GUIA3_26" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta26_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta26_3casinunca" name="GUIA3_26" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta26_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta26_3nunca" name="GUIA3_26" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta27_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_27" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    27. Puedo decidir la velocidad a la que realizo <br> mis actividades en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta27_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta27_3siempre" name="GUIA3_27" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta27_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta27_3casi" name="GUIA3_27" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta27_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta27_3algunas" name="GUIA3_27" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta27_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta27_3casinunca" name="GUIA3_27" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta27_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta27_3nunca" name="GUIA3_27" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta28_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_28" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    28. Puedo cambiar el orden de las actividades <br> que realizo en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta28_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta28_3siempre" name="GUIA3_28" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta28_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta28_3casi" name="GUIA3_28" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta28_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta28_3algunas" name="GUIA3_28" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta28_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta28_3casinunca" name="GUIA3_28" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta28_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta28_3nunca" name="GUIA3_28" value="4">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con cualquier tipo de cambio que ocurra en su trabajo (considere los últimos cambios realizados).</p>
                            </div>
                            <div id="pregunta29_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_29" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    29. Los cambios que se presentan en mi <br>trabajo dificultan mi labor
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta29_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta29_3siempre" name="GUIA3_29" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta29_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta29_3casi" name="GUIA3_29" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta29_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta29_3algunas" name="GUIA3_29" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta29_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta29_3casinunca" name="GUIA3_29" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta29_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta29_3nunca" name="GUIA3_29" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta30_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_30" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    30. Cuando se presentan cambios en mi <br> trabajo se tienen en cuenta mis ideas o aportaciones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta30_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta30_3siempre" name="GUIA3_30" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta30_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta30_3casi" name="GUIA3_30" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta30_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta30_3algunas" name="GUIA3_30" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta30_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta30_3casinunca" name="GUIA3_30" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta30_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta30_3nunca" name="GUIA3_30" value="4">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con la capacitación e información que se le proporciona sobre su trabajo.</p>
                            </div>

                            <div id="pregunta31_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_31" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    31. Me informan con claridad cuáles son mis funciones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta31_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta31_3siempre" name="GUIA3_31" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta31_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta31_3casi" name="GUIA3_31" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta31_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta31_3algunas" name="GUIA3_31" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta31_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta31_3casinunca" name="GUIA3_31" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta31_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta31_3nunca" name="GUIA3_31" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta32_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_32" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    32. Me explican claramente los resultados que <br> debo obtener en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta32_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta32_3siempre" name="GUIA3_32" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta32_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta32_3casi" name="GUIA3_32" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta32_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta32_3algunas" name="GUIA3_32" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta32_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta32_3casinunca" name="GUIA3_32" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta32_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta32_3nunca" name="GUIA3_32" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta33_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_33" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    33. Me explican claramente los objetivos de mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta33_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta33_3siempre" name="GUIA3_33" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta33_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta33_3casi" name="GUIA3_33" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta33_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta33_3algunas" name="GUIA3_33" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta33_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta33_3casinunca" name="GUIA3_33" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta33_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta33_3nunca" name="GUIA3_33" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta34_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_34" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    34. Me informan con quién puedo resolver problemas o asuntos de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta34_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta34_3siempre" name="GUIA3_34" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta34_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta34_3casi" name="GUIA3_34" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta34_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta34_3algunas" name="GUIA3_34" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta34_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta34_3casinunca" name="GUIA3_34" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta34_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta34_3nunca" name="GUIA3_34" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta35_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_35" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    35. Me permiten asistir a capacitaciones relacionadas con mi trabajo

                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta35_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta35_3siempre" name="GUIA3_35" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta35_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta35_3casi" name="GUIA3_35" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta35_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta35_3algunas" name="GUIA3_35" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta35_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta35_3casinunca" name="GUIA3_35" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta35_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta35_3nunca" name="GUIA3_35" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta36_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_36" aria-hidden="true" data-toggle="tooltip" title=""></i>

                                    36. Recibo capacitación útil para hacer mi <br>
                                    trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta36_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta36_3siempre" name="GUIA3_36" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta36_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta36_3casi" name="GUIA3_36" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta36_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta36_3algunas" name="GUIA3_36" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta36_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta36_3casinunca" name="GUIA3_36" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta36_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta36_3nunca" name="GUIA3_36" value="4">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con el o los jefes con quien tiene contacto.</p>
                            </div>
                            <div id="pregunta37_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_37" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    37. Mi jefe me ayuda organizar mejor el <br>trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta37_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta37_3siempre" name="GUIA3_37" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta37_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta37_3casi" name="GUIA3_37" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta37_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta37_3algunas" name="GUIA3_37" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta37_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta37_3casinunca" name="GUIA3_37" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta37_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta37_3nunca" name="GUIA3_37" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta38_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_38" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    38. Mi jefe tiene en cuenta mis puntos de<br>vista y opiniones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta38_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta38_3siempre" name="GUIA3_38" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta38_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta38_3casi" name="GUIA3_38" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta38_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta38_3algunas" name="GUIA3_38" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta38_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta38_3casinunca" name="GUIA3_38" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta38_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta38_3nunca" name="GUIA3_38" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta39_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_39" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    39. Mi jefe me comunica a tiempo la <br> información relacionada con el trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta39_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta39_3siempre" name="GUIA3_39" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta39_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta39_3casi" name="GUIA3_39" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta39_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta39_3algunas" name="GUIA3_39" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta39_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta39_3casinunca" name="GUIA3_39" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta39_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta39_3nunca" name="GUIA3_39" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta40_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_40" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    40. La orientación que me da mi jefe me <br>ayuda a realizar mejor mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta40_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta40_3siempre" name="GUIA3_40" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta40_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta40_3casi" name="GUIA3_40" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta40_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta40_3algunas" name="GUIA3_40" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta40_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta40_3casinunca" name="GUIA3_40" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta40_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta40_3nunca" name="GUIA3_40" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta41_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_41" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    41. Mi jefe ayuda a solucionar los problemas <br> que se presentan en el trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta41_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta41_3siempre" name="GUIA3_41" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta41_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta41_3casi" name="GUIA3_41" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta41_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta41_3algunas" name="GUIA3_41" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta41_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta41_3casinunca" name="GUIA3_41" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta41_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta41_3nunca" name="GUIA3_41" value="4">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes se refieren a las relaciones con sus compañeros.</p>
                            </div>
                            <div id="pregunta42_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_42" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    42. Puedo confiar en mis compañeros de <br> trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta42_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta42_3siempre" name="GUIA3_42" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta42_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta42_3casi" name="GUIA3_42" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta42_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta42_3algunas" name="GUIA3_42" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta42_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta42_3casinunca" name="GUIA3_42" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta42_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta42_3nunca" name="GUIA3_42" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta43_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_43" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    43. Entre compañeros solucionamos los <br> problemas de trabajo de forma respetuosa
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta43_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta43_3siempre" name="GUIA3_43" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta43_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta43_3casi" name="GUIA3_43" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta43_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta43_3algunas" name="GUIA3_43" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta43_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta43_3casinunca" name="GUIA3_43" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta43_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta43_3nunca" name="GUIA3_43" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta44_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_44" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    44. En mi trabajo me hacen sentir parte del<br>
                                    grupo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta44_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta44_3siempre" name="GUIA3_44" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta44_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta44_3casi" name="GUIA3_44" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta44_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta44_3algunas" name="GUIA3_44" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta44_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta44_3casinunca" name="GUIA3_44" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta44_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta44_3nunca" name="GUIA3_44" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta45_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_45" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    45. Cuando tenemos que realizar trabajo de <br> equipo los compañeros colaboran
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta45_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta45_3siempre" name="GUIA3_45" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta45_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta45_3casi" name="GUIA3_45" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta45_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta45_3algunas" name="GUIA3_45" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta45_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta45_3casinunca" name="GUIA3_45" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta45_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta45_3nunca" name="GUIA3_45" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta46_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_46" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    46. Mis compañeros de trabajo me ayudan <br> cuando tengo dificultades
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta46_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta46_3siempre" name="GUIA3_46" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta46_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta46_3casi" name="GUIA3_46" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta46_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta46_3algunas" name="GUIA3_46" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta46_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta46_3casinunca" name="GUIA3_46" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta46_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta46_3nunca" name="GUIA3_46" value="4">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con la información que recibe sobre su rendimiento en el trabajo, el reconocimiento, el sentido
                                    de pertenencia y la estabilidad que el ofrece su trabajo.</p>
                            </div>
                            <div id="pregunta47_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_47" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    47. Me informan sobre lo que hago bien en mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta47_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta47_3siempre" name="GUIA3_47" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta47_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta47_3casi" name="GUIA3_47" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta47_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta47_3algunas" name="GUIA3_47" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta47_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta47_3casinunca" name="GUIA3_47" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta47_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta47_3nunca" name="GUIA3_47" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta48_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_48" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    48. La forma como evalúan mi trabajo en mi <br>centro de trabajo me ayuda a mejorar mi desempeño
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta48_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta48_3siempre" name="GUIA3_48" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta48_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta48_3casi" name="GUIA3_48" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta48_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta48_3algunas" name="GUIA3_48" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta48_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta48_3casinunca" name="GUIA3_48" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta48_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta48_3nunca" name="GUIA3_48" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta49_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_49" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    49. En mi centro de trabajo me pagan a <br> tiempo mi salario
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta49_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta49_3siempre" name="GUIA3_49" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta49_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta49_3casi" name="GUIA3_49" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta49_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta49_3algunas" name="GUIA3_49" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta49_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta49_3casinunca" name="GUIA3_49" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta49_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta49_3nunca" name="GUIA3_49" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta50_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_50" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    50. El pago que recibo es el que merezco por <br> el trabajo que realizo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta50_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta50_3siempre" name="GUIA3_50" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta50_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta50_3casi" name="GUIA3_50" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta50_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta50_3algunas" name="GUIA3_50" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta50_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta50_3casinunca" name="GUIA3_50" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta50_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta50_3nunca" name="GUIA3_50" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta51_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_51" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    51. Si obtengo los resultados esperados en mi trabajo me recompensan o reconocen
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta51_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta51_3siempre" name="GUIA3_51" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta51_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta51_3casi" name="GUIA3_51" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta51_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta51_3algunas" name="GUIA3_51" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta51_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta51_3casinunca" name="GUIA3_51" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta51_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta51_3nunca" name="GUIA3_51" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta52_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_52" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    52. Las personas que hacen bien el trabajo <br> pueden crecer laboralmente
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta52_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta52_3siempre" name="GUIA3_52" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta52_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta52_3casi" name="GUIA3_52" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta52_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta52_3algunas" name="GUIA3_52" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta52_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta52_3casinunca" name="GUIA3_52" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta52_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta52_3nunca" name="GUIA3_52" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta53_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_53" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    53. Considero que mi trabajo es estable
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta53_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta53_3siempre" name="GUIA3_53" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta53_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta53_3casi" name="GUIA3_53" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta53_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta53_3algunas" name="GUIA3_53" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta53_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta53_3casinunca" name="GUIA3_53" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta53_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta53_3nunca" name="GUIA3_53" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta54_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_54" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    54. En mi trabajo existe continua rotación de personal
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta54_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta54_3siempre" name="GUIA3_54" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta54_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta54_3casi" name="GUIA3_54" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta54_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta54_3algunas" name="GUIA3_54" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta54_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta54_3casinunca" name="GUIA3_54" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta54_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta54_3nunca" name="GUIA3_54" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta55_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_55" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    55. Siento orgullo de laborar en este centro de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta55_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta55_3siempre" name="GUIA3_55" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta55_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta55_3casi" name="GUIA3_55" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta55_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta55_3algunas" name="GUIA3_55" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta55_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta55_3casinunca" name="GUIA3_55" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta55_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta55_3nunca" name="GUIA3_55" value="4">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta56_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_56" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    56. Me siento comprometido con mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta56_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta56_3siempre" name="GUIA3_56" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta56_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta56_3casi" name="GUIA3_56" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta56_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta56_3algunas" name="GUIA3_56" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta56_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta56_3casinunca" name="GUIA3_56" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta56_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta56_3nunca" name="GUIA3_56" value="4">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las personas siguientes están relacionados con actos de violencia laboral (malos tratos, acoso,
                                    hostigamiento, acoso psicológico).</p>
                            </div>
                            <div id="pregunta57_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_57" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    57. En mi trabajo puedo expresarme <br> libremente sin interrupciones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta57_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta57_3siempre" name="GUIA3_57" value="0">
                                    </div>
                                    <div>
                                        <label for="preguta57_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta57_3casi" name="GUIA3_57" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta57_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta57_3algunas" name="GUIA3_57" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta57_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta57_3casinunca" name="GUIA3_57" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta57_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta57_3nunca" name="GUIA3_57" value="4">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta58_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_58" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    58. Recibo críticas constantes a mi persona <br> y/o trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta58_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta58_3siempre" name="GUIA3_58" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta58_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta58_3casi" name="GUIA3_58" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta58_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta58_3algunas" name="GUIA3_58" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta58_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta58_3casinunca" name="GUIA3_58" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta58_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta58_3nunca" name="GUIA3_58" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta59_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_59" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    59. Recibo burlas, calumnias, difamaciones, humillaciones o ridiculizaciones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta59_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta59_3siempre" name="GUIA3_59" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta59_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta59_3casi" name="GUIA3_59" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta59_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta59_3algunas" name="GUIA3_59" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta59_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta59_3casinunca" name="GUIA3_59" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta59_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta59_3nunca" name="GUIA3_59" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta60_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_60" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    60. Se ignora mi presencia o se me excluye de <br> las reuniones de trabajo y en la toma de decisiones
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta60_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta60_3siempre" name="GUIA3_60" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta60_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta60_3casi" name="GUIA3_60" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta60_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta60_3algunas" name="GUIA3_60" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta60_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta60_3casinunca" name="GUIA3_60" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta60_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta60_3nunca" name="GUIA3_60" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta61_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_61" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    61. Se manipulan las situaciones de trabajo <br> para hacerme parecer un mal trabajador
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta61_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta61_3siempre" name="GUIA3_61" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta61_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta61_3casi" name="GUIA3_61" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta61_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta61_3algunas" name="GUIA3_61" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta61_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta61_3casinunca" name="GUIA3_61" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta61_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta61_3nunca" name="GUIA3_61" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta62_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_62" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    62. Se ignoran mis éxitos laborales y se <br> atribuyen a otros trabajadores
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta62_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta62_3siempre" name="GUIA3_62" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta62_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta62_3casi" name="GUIA3_62" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta62_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta62_3algunas" name="GUIA3_62" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta62_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta62_3casinunca" name="GUIA3_62" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta62_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta62_3nunca" name="GUIA3_62" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta63_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_63" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    63. Me bloquean o impiden las oportunidades <br> que tengo para obtener ascenso o mejora en <br> mi trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta63_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta63_3siempre" name="GUIA3_63" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta63_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta63_3casi" name="GUIA3_63" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta63_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta63_3algunas" name="GUIA3_63" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta63_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta63_3casinunca" name="GUIA3_63" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta63_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta63_3nunca" name="GUIA3_63" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta64_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_64" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    64. He presenciado actos de violencia en mi <br> centro de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta64_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta64_3siempre" name="GUIA3_64" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta64_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta64_3casi" name="GUIA3_64" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta64_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta64_3algunas" name="GUIA3_64" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta64_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta64_3casinunca" name="GUIA3_64" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta64_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta64_3nunca" name="GUIA3_64" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <hr>
                                <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Las preguntas siguientes están relacionadas con la atención a clientes y usuarios.</p>
                            </div>

                            <div id="pregunta73_3" class="mt-5" style="display: flex; align-items: center; margin-bottom: 10px;">
                                <p style="margin: 0; white-space: nowrap; margin-right: 10px;"><i class="fa fa-info-circle" id="Exp3_1ADI" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    En mi trabajo debo brindar servicio a clientes o usuarios:
                                </p>
                                <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi1_3si" style="line-height: 1; margin-right: 5px;">Sí</label>
                                        <input type="radio" id="preguntaadi1_3si" name="GUIA3_73" value="1" style="vertical-align: middle;" onchange="clientesyusuariosguia3()">
                                    </div>
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi1_3no" style="line-height: 1; margin-right: 5px;">No</label>
                                        <input type="radio" id="preguntaadi1_3no" name="GUIA3_73" value="0" style="vertical-align: middle;" onchange="clientesyusuariosguia3()">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6 class="text-nota">Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO" pase a las preguntas de la sección siguiente.
                                </h6>
                            </div>
                        </div>

                        <div id="seccion2_3" class="mt-2" style="display: none; padding: 10px;">
                            <div id="pregunta65_3" class="mt-3" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_65" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    65. Atiendo clientes o usuarios muy enojados
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta65_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta65_3siempre" name="GUIA3_65" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta65_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta65_3casi" name="GUIA3_65" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta65_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta65_3algunas" name="GUIA3_65" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta65_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta65_3casinunca" name="GUIA3_65" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta65_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta65_3nunca" name="GUIA3_65" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta66_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_66" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    66. Mi trabajo me exige atender personas muy necesitadas de ayuda o enfermas
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta66_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta66_3siempre" name="GUIA3_66" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta66_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta66_3casi" name="GUIA3_66" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta66_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta66_3algunas" name="GUIA3_66" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta66_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta66_3casinunca" name="GUIA3_66" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta66_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta66_3nunca" name="GUIA3_66" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta67_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_67" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    67. Para hacer mi trabajo debo demostrar sentimientos distintos a los míos
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta67_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta67_3siempre" name="GUIA3_67" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta67_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta67_3casi" name="GUIA3_67" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta67_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta67_3algunas" name="GUIA3_67" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta67_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta67_3casinunca" name="GUIA3_67" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta67_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta67_3nunca" name="GUIA3_67" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta68_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_68" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    68. Mi trabajo me exige atender situaciones de violencia
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta68_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta68_3siempre" name="GUIA3_68" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta68_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta68_3casi" name="GUIA3_68" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta68_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta68_3algunas" name="GUIA3_68" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta68_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta68_3casinunca" name="GUIA3_68" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta68_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta68_3nunca" name="GUIA3_68" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="seccion3_3" class="mt-2" style="display: block; padding: 10px;">
                            <div id="pregunta74_3" class="mt-2" style="display: flex; align-items: center; margin-bottom: 10px;">
                                <p style="margin: 0; white-space: nowrap; margin-right: 10px;"><i class="fa fa-info-circle" id="Exp3_2ADI" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    Soy jefe de otros trabajadores:
                                </p>
                                <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi2_3si" style="line-height: 1; margin-right: 5px;">Sí</label>
                                        <input type="radio" id="preguntaadi2_3si" name="GUIA3_74" value="1" style="vertical-align: middle;" onchange="jefetrabajadoresguia3()">
                                    </div>
                                    <div style="display: inline-block;">
                                        <label for="preguntaadi2_3no" style="line-height: 1; margin-right: 5px;">No</label>
                                        <input type="radio" id="preguntaadi2_3no" name="GUIA3_74" value="0" style="vertical-align: middle;" onchange="jefetrabajadoresguia3()">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6 class="text-nota">Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO", ha concluido el cuestionario.
                                </h6>
                            </div>
                        </div>


                        <div id="seccion4_3" class="mt-2" style="display: none; padding: 10px;">
                            <div class="mt-3">
                                <h6>Las siguientes preguntas están relacionadas con las actitudes de los trabajadores que supervisa.
                                </h6>
                            </div>
                            <div id="pregunta69_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_69" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    69. Comunican tarde los asuntos de trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta69_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta69_3siempre" name="GUIA3_69" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta69_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta69_3casi" name="GUIA3_69" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta69_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta69_3algunas" name="GUIA3_69" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta69_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta69_3casinunca" name="GUIA3_69" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta69_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta69_3nunca" name="GUIA3_69" value="0">
                                    </div>
                                </div>
                            </div>
                            <div id="pregunta70_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_70" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    70. Dificultan el logro de los resultados del <br> trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta70_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta70_3siempre" name="GUIA3_70" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta70_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta70_3casi" name="GUIA3_70" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta70_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta70_3algunas" name="GUIA3_70" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta70_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta70_3casinunca" name="GUIA3_70" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta70_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta70_3nunca" name="GUIA3_70" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta71_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_71" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    71. Cooperan poco cuando se necesita
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta71_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta71_3siempre" name="GUIA3_71" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta71_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta71_3casi" name="GUIA3_71" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta71_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta71_3algunas" name="GUIA3_71" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta71_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta71_3casinunca" name="GUIA3_71" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta71_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta71_3nunca" name="GUIA3_71" value="0">
                                    </div>
                                </div>
                            </div>

                            <div id="pregunta72_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                                <p style="margin: 0; flex: 1;"><i class="fa fa-info-circle" id="Exp3_72" aria-hidden="true" data-toggle="tooltip" title=""></i>
                                    72. Ignoran las sugerencias para mejorar <br> su trabajo
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <label for="preguta72_3siempre" class="radio-label" style="margin-right: 5px;">Siempre</label>
                                        <input type="radio" class="radio-group" id="preguta72_3siempre" name="GUIA3_72" value="4">
                                    </div>
                                    <div>
                                        <label for="preguta72_3casi" class="radio-label" style="margin-right: 5px;">Casi siempre</label>
                                        <input type="radio" class="radio-group" id="preguta72_3casi" name="GUIA3_72" value="3">
                                    </div>
                                    <div>
                                        <label for="preguta72_3algunas" class="radio-label" style="margin-right: 5px;">Algunas veces</label>
                                        <input type="radio" class="radio-group" id="preguta72_3algunas" name="GUIA3_72" value="2">
                                    </div>
                                    <div>
                                        <label for="preguta72_3casinunca" class="radio-label" style="margin-right: 5px;">Casi nunca</label>
                                        <input type="radio" class="radio-group" id="preguta72_3casinunca" name="GUIA3_72" value="1">
                                    </div>
                                    <div>
                                        <label for="preguta72_3nunca" class="radio-label" style="margin-right: 5px;">Nunca</label>
                                        <input type="radio" class="radio-group" id="preguta72_3nunca" name="GUIA3_72" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger" id="guardar_guia3" onclick="validarGuia5()">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="col-datos" class="col-3">
            <div id="datos" class="datos">
                <div class="info-section text-center">
                    <i class="fa fa-info-circle fa-2x mb-1"></i>
                    <p style="font-style: italic;"><strong>Presione el icono para obtener una explicación detallada</strong></p>
                </div>
              
                <h5><i class="fa fa-user"></i>Datos Generales</h5>
                <div class="info-section">
                    <p><strong><i class="fa fa-id-card"></i> Nombre del trabajador:</strong><span id="nombre-trabajador"></span></p>
                    <p><strong><i class="fa fa-venus-mars"></i> Sexo:</strong><span id="genero-trabajador"></span></p>
                    <p><strong><i class="fa fa-envelope"></i> Correo:</strong><span id="correo-trabajador"></span></p>
                </div>
             
                <h5><i class="fa fa-user-md"></i> Psicólogo</h5>
                <div class="info-section">
                    <p><strong><i class="fa fa-user"></i> Nombre del Psicólogo:</strong><span id="nombre-psicologo"></span></p>
                    <p><strong><i class="fa fa-phone"></i> Número de teléfono:</strong><span id="telefono-psicologo"></span></p>
                    <p><strong><i class="fa fa-clock-o"></i> Horario de atención:</strong><span id="horario-psicologo"></span></p>
                </div>
              
                <h5><i class="fa fa-user-md"></i> Empresa</h5>
                <div class="info-section">
                    <p><strong><i class="fa fa-building"></i> Nombre de la empresa:</strong><span id="nombre-empresa">Results In Performance</span></p>
                    <p><strong><i class="fa fa-phone"></i> Número de teléfono:</strong><span id="telefono-empresa"> +52 993 14 72 682</span></p>
                    <p><strong><i class="fa fa-clock-o"></i> Horario de atención:</strong><span id="horario-empresa">Lunes-Sábado de 8:00 - 18:00 hrs</span></p>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal para el aviso de privacidad -->
    <div id="avisoPrivacidadModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="avisoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="avisoPrivacidadModalLabel">Aviso de Privacidad y Permisos de Cámara</h5>
                    <img src="/assets/images/Logo_Color_results_original.png" alt="Imagen de Privacidad" style="width: 220px;">
                </div>

                <div class="modal-body">
                    <p>
                        Para continuar usando esta aplicación, debe aceptar nuestro aviso de privacidad y otorgar los permiso de uso de cámara web.
                    </p>
                    <h6>Aviso de Privacidad</h6>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="aceptarPermisos">Acepto y continuo</button>
                </div>
            </div>
        </div>
    </div>
    <div id="avisoPermisosModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="avisoPermisosModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="avisoPermisosModalLabel">Permiso requerido</h5>
                </div>
                <div class="modal-body">
                    <h6>Permiso de uso de cámara web requerido</h6>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div id="fotoModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="form-foto" enctype="multipart/form-data">
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div id="instruccionesFoto" class="text-center">
                            <p>Para continuar, mire fijamente a la cámara por unos segundos y cuando este listo presione el botón para capturar y guardar su foto</p>
                        </div>
                        <div id="video-container"></div>
                        <input type="file" id="imagen" name="foto" style="display:none;">
                    </form>


                    <!-- Spinner de carga -->
                    <div id="loadingSpinner" class="text-center" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <p>Guardando la foto, por favor espera...</p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="tomar-foto">Tomar foto y continuar</button>
                </div>
            </div>
        </div>
    </div>
    <div id="instruccionesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="instruccionesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="instruccionesModalLabel">Instrucciones</h5>
                    <img src="/assets/images/Logo_Color_results_original.png" alt="Imagen de Privacidad" style="width: 220px;">
                </div>

                <div class="modal-body">
                    <p style="font-size: 1rem; color: #333;">
                        <i class="fa fa-info-circle" style="color: #007bff;"></i>
                        Por favor, lea detenidamente cada uno de los siguientes incisos y responda conforme a su experiencia.
                    </p>

                    <h6 style="margin-top: 20px; font-weight: bold; color: #dc3545;">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: #dc3545;"></i> Aviso Importante
                    </h6>

                    <p style="font-size: 0.9rem; color: #555; font-style: italic;">
                        Recuerde que esto no es un examen, por lo que no existen respuestas correctas o incorrectas.
                        <br>
                    </p>
                    <p style="font-size: 0.9rem; color: #555;">
                        Esta herramienta evalúa factores de riesgo psicosocial en los centros de trabajo, con base en la NOM-035-STPS-2018 establecida en los Estados Unidos Mexicanos.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="instruccionesEntendidas" onclick="instruccionesEntendidas()">
                        <i class="fa fa-check" aria-hidden="true"></i> Entendido
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal guia 5 -->
    <div class="modal fade" id="guia5Modal" tabindex="-1" role="dialog" aria-labelledby="guia5ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guia5ModalLabel">Guía de referencia V - Datos del Trabajador</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="margin: 0; flex: 1; font-style: italic;"><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Valide que sus datos sean correctos o modifique antes de guardar</p>
                    <hr>
                    <form enctype="multipart/form-data" method="post" name="guia_5" id="guia_5">
                        {!! csrf_field() !!}

                        <!-- Pregunta 1: Edad -->
                        <div class="form-group">
                            <label for="edad">Edad:</label>
                            <select name="edad" id="edad" class="form-control" required>
                                <option value="" disabled selected>Seleccione su edad</option>
                                <option value="menor_20">Menor de 20</option>
                                <option value="20_30">20 - 30</option>
                                <option value="31_40">31 - 40</option>
                                <option value="41_50">41 - 50</option>
                                <option value="mayor_50">Mayor de 50</option>
                            </select>
                        </div>

                        <!-- Pregunta 2: Sexo -->
                        <div class="form-group">
                            <label for="sexo">Sexo:</label>
                            <select name="sexo" id="sexo" class="form-control" required>
                                <option value="" disabled selected>Seleccione su sexo</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <!-- Pregunta 3: Estado civil -->
                        <div class="form-group">
                            <label for="estado_civil">Estado civil:</label>
                            <select name="estado_civil" id="estado_civil" class="form-control" required>
                                <option value="" disabled selected>Seleccione su estado civil</option>
                                <option value="soltero">Soltero/a</option>
                                <option value="casado">Casado/a</option>
                                <option value="union_libre">Unión libre</option>
                                <option value="divorciado">Divorciado/a</option>
                                <option value="viudo">Viudo/a</option>
                                <option value="NA">Prefiero no decirlo/a</option>
                            </select>
                        </div>

                        <!-- Pregunta 4: Nivel de estudios -->
                        <div class="form-group">
                            <label for="nivel_estudios">Nivel de estudios:</label>
                            <select name="nivel_estudios" id="nivel_estudios" class="form-control" required>
                                <option value="" disabled selected>Seleccione su nivel de estudios</option>

                                <option value="primariaIncompleta">Primaria Incompleta</option>
                                <option value="primariaTerminada">Primaria Terminada</option>

                                <option value="secundariaIncompleta">Secundaria Incompleta</option>
                                <option value="secundariaTerminada">Secundaria Terminada</option>

                                <option value="preparatoriaIncompleta">Preparatoria o bachillerato Incompleta</option>
                                <option value="preparatoriaTerminada">Preparatoria o bachillerato Terminada</option>

                                <option value="tecnicoSuperiorIncompleta">Técnico Superior Incompleta</option>
                                <option value="tecnicoSuperiorTerminada">Técnico Superior Terminada</option>

                                <option value="licenciaturaIncompleta">Licenciatura Incompleta</option>
                                <option value="licenciaturaTerminada">Licenciatura Terminada</option>

                                <option value="especialidadIncompleta">Especialidad Incompleta</option>
                                <option value="especialidadTerminada">Especialidad Terminada</option>

                                <option value="maestriaIncompleta">Maestría Incompleta</option>
                                <option value="maestriaTerminada">Maestría Terminada</option>

                                <option value="doctoradoIncompleta">Doctorado Incompleta</option>
                                <option value="doctoradoTerminada">Doctorado Terminada</option>

                                <option value="postdoctoradoIncompleta">Postdoctorado Incompleta</option>
                                <option value="postdoctoradoTerminada">Postdoctorado Terminada</option>

                            </select>
                        </div>

                        <!-- Pregunta 5: Tipo de puesto -->
                        <div class="form-group">
                            <label for="tipo_puesto">Tipo de puesto:</label>
                            <select name="tipo_puesto" id="tipo_puesto" class="form-control" required>
                                <option value="" disabled selected>Seleccione su tipo de puesto</option>
                                <option value="operativo">Operativo</option>
                                <option value="tecnico">Técnico</option>
                                <option value="profesional">Profesional</option>
                                <option value="directivo">Directivo</option>
                            </select>
                        </div>

                        <!-- Pregunta 6: Tipo de contratación -->
                        <div class="form-group">
                            <label for="tipo_contratacion">Tipo de contratación:</label>
                            <select name="tipo_contratacion" id="tipo_contratacion" class="form-control" required>
                                <option value="" disabled selected>Seleccione su tipo de contratación</option>
                                <option value="base">Base</option>
                                <option value="temporal">Temporal</option>
                                <option value="honorarios">Honorarios</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <!-- Pregunta 7: Tipo de jornada -->
                        <div class="form-group">
                            <label for="tipo_jornada">Tipo de jornada:</label>
                            <select name="tipo_jornada" id="tipo_jornada" class="form-control" required>
                                <option value="" disabled selected>Seleccione su tipo de jornada</option>
                                <option value="diurna">Diurna</option>
                                <option value="nocturna">Nocturna</option>
                                <option value="mixta">Mixta</option>
                            </select>
                        </div>

                        <!-- Pregunta 8: Tiempo en el puesto actual -->
                        <div class="form-group">
                            <label for="tiempo_puesto">Tiempo en el puesto actual:</label>
                            <select name="tiempo_puesto" id="tiempo_puesto" class="form-control" required>
                                <option value="" disabled selected>Seleccione el tiempo en el puesto</option>
                                <option value="menos_1_ano">Menos de 1 año</option>
                                <option value="1_4_anos">1 a 4 años</option>
                                <option value="5_9_anos">5 a 9 años</option>
                                <option value="10_anos_o_mas">10 años o más</option>
                            </select>
                        </div>

                        <!-- Pregunta 9: Tiempo en la empresa -->
                        <div class="form-group">
                            <label for="tiempo_empresa">Tiempo en la empresa:</label>
                            <select name="tiempo_empresa" id="tiempo_empresa" class="form-control" required>
                                <option value="" disabled selected>Seleccione el tiempo en la empresa</option>
                                <option value="menos_1_ano">Menos de 1 año</option>
                                <option value="1_4_anos">1 a 4 años</option>
                                <option value="5_9_anos">5 a 9 años</option>
                                <option value="10_anos_o_mas">10 años o más</option>
                            </select>
                        </div>

                        <!-- Botón de envío -->
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" onclick="submitGuia1y3()">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        var requiereGuia1 = <?php echo json_encode($guia1); ?>;
        var requiereGuia2 = <?php echo json_encode($guia2); ?>;
        var requiereGuia3 = <?php echo json_encode($guia3); ?>;
        var id = <?php echo json_encode($id); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            $("#TRABAJADOR_ID").val(id);

            mostrarGuias(requiereGuia1, requiereGuia2, requiereGuia3);
            cargarExplicaciones();
            botonradio('radio-group');
            scrolldatos();
            consultarDatos();
            consultarRespuestasGuardadas();

        });
    </script>


    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- <script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="/assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.4/dist/sweetalert2.all.min.js"></script>
    <script src="/js_sitio/guias.js"></script>



</body>


</html>