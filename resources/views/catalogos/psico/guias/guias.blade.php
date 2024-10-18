<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title>Results In Performance</title>


    <!-- Bootstrap Core CSS -->
    {{-- <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}
    {{-- <link href="/css/style.css" rel="stylesheet"> --}}

    <link href="/assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/scss/icons/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/scss/icons/material-design-iconic-font/css/materialdesignicons.min.css">
    <!-- You can change the theme colors from here -->
    <link href="/css/colors/color_RIP.css" id="theme" rel="stylesheet">
    <!--alerts CSS -->
    <link href="/assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!-- Date picker plugins css -->
    <link href="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    

</head>


<body>

    <style>
        body {
            font-family: "Poppins", sans-serif;

        }



        .card-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 600px;
            margin-left: 20px;
        }

        #guia1 {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 180%;
            transition: transform 0.3s ease;

        }

        #guia2 {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 180%;
            transition: transform 0.3s ease;
            display: block;
        }

        #guia3 {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 180%;
            transition: transform 0.3s ease;
        }

        .card-title {
            font-size: 18px;
            text-align: center;
            display: inline-block;
            width: 100%;
        }



        .card {
            width: 100%;
        }


        i {
            color: #99abb4;
        }
    </style>

    <div id="titulo" class="mt-4">
        <div style="display: flex; align-items: center; justify-content: center;">
            <img src="/assets/images/Logo_Color_results_original.png" alt="Logo" style="margin-right: 330px; width: 220px;">
            <h1 style="text-align: center; font-size: 48px;">Results In Performance</h1>
        </div>
    </div>

    <div class="card-container mt-5">

        <div id="guia1" class="card" style="display: block">
            <h6 style="text-align: center">Guía de referencia I</h6>
            <h3 class="card-title"><b>CUESTIONARIO PARA IDENTIFICAR A LOS TRABAJADORES QUE FUERON SUJETOS A ACONTECIMIENTOS TRAUMÁTICOS SEVEROS</b></h3>

            <form enctype="multipart/form-data" method="post" name="guia_1" id="guia_1">
                {!! csrf_field() !!}

                <div id="seccion1" style="padding: 10px; ">
                    <div id="titulo1">
                        <h5 style="text-align: left; width: 70%;"><b>I.- Acontecimiento traumático severo</b></h5>
                    </div>
                    <div id="pregunta1" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_1" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha presenciado o sufrido alguna vez, durante o con motivo del trabajo un acontecimiento como los siguientes</p>
                        <div style="display: none; align-items: center; gap: 20px;">
                            <div>
                                <label for="pregunta1_si" style="margin-right: 5px;">Sí</label>
                                <input type="radio" id="pregunta1_si" name="GUIA1_1" value="1" required onchange="guia1()">
                            </div>

                            <div>
                                <label for="pregunta1_no" style="margin-right: 5px;">No</label>
                                <input type="radio" id="pregunta1_no" name="GUIA1_1" value="0" onchange="guia1()">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta2" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_2" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Accidente que tenga como consecuencia la muerte, la pérdida de un miembro o una lesión grave?</p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="pregunta2_si" style="margin-right: 5px;">Sí</label>
                                <input type="radio" id="pregunta2_si" name="GUIA1_2" value="1" required onchange="guia1()">
                            </div>
                            <div>
                                <label for="pregunta2_no" style="margin-right: 5px;">No</label>
                                <input type="radio" id="pregunta2_no" name="GUIA1_2" value="0" onchange="guia1()">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta3" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_3" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Asaltos?</p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="pregunta3_si" style="margin-right: 5px;">Sí</label>
                                <input type="radio" id="pregunta3_si" name="GUIA1_3" value="1" required onchange="guia1()">
                            </div>

                            <div>
                                <label for="pregunta3_no" style="margin-right: 5px;">No</label>
                                <input type="radio" id="pregunta3_no" name="GUIA1_3" value="0" onchange="guia1()">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_4" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Actos violentos que derivaron en lesiones graves?</p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="pregunta4_si" style="margin-right: 5px;">Sí</label>
                                <input type="radio" id="pregunta4_si" name="GUIA1_4" value="1" required onchange="guia1()">
                            </div>

                            <div>
                                <label for="pregunta4_no" style="margin-right: 5px;">No</label>
                                <input type="radio" id="pregunta4_no" name="GUIA1_4" value="0" onchange="guia1()">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_5" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Secuestro?</p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="pregunta5_si" style="margin-right: 5px;">Sí</label>
                                <input type="radio" id="pregunta5_si" name="GUIA1_5" value="1" required onchange="guia1()">
                            </div>

                            <div>
                                <label for="pregunta5_no" style="margin-right: 5px;">No</label>
                                <input type="radio" id="pregunta5_no" name="GUIA1_5" value="0" onchange="guia1()">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta6" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_6" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Amenazas?</p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="pregunta6_si" style="margin-right: 5px;">Sí</label>
                                <input type="radio" id="pregunta6_si" name="GUIA1_6" value="1" required onchange="guia1()">
                            </div>

                            <div>
                                <label for="pregunta6_no" style="margin-right: 5px;">No</label>
                                <input type="radio" id="pregunta6_no" name="GUIA1_6" value="0" onchange="guia1()">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta7" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_7" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            O cualquier otro que ponga en riesgo su vida o salud, y/o la de otras personas?</p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="pregunta7_si" style="margin-right: 5px;">Sí</label>
                                <input type="radio" id="pregunta7_si" name="GUIA1_7" value="1" required onchange="guia1()">
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
                        <h5 style="text-align: left; width: 70%;"><b>II.- Recuerdos persistentes sobre el acontecimiento (durante el último mes):</b></h5>
                    </div>
                    <div id="pregunta8" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_8" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha tenido recuerdos recurrentes sobre el acontecimiento que le provocan malestares?</p>
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
                    <div id="pregunta9" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_9" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha tenido sueños de carácter recurrente sobre el acontecimiento, que le producen malestar?</p>
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
                        <h5 style="text-align: left; width: 70%;"><b>III.- Esfuerzo por evitar circunstancias parecidas o asociadas al acontecimiento (durante el último mes):</b></h5>
                    </div>
                    <div id="pregunta10" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_10" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Se ha esforzado por evitar todo tipo de sentimientos, conversaciones o situaciones que le puedan recordar el acontecimiento?</p>
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
                    <div id="pregunta11" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_11" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Se ha esforzado por evitar todo tipo de actividades, lugares o personas que motivan recuerdos del acontecimiento?</p>
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
                    <div id="pregunta12" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_12" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha tenido dificultad para recordar alguna parte importante del evento?</p>
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
                    <div id="pregunta13" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_13" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha disminuido su interés en sus actividades cotidianas?</p>
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
                    <div id="pregunta14" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_14" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Se ha sentido usted alejado o distante de los demás?</p>
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
                    <div id="pregunta15" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_15" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha notado que tiene dificultad para expresar sus sentimientos?</p>
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
                    <div id="pregunta16" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_16" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha tenido la impresión de que su vida se va a acortar, que va a morir antes que otras personas o que tiene un futuro limitado?</p>
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
                        <h5 style="text-align: left; width: 70%;"><b>IV.- Afectación (durante el último mes):</b></h5>
                    </div>
                    <div id="pregunta17" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_17" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha tenido usted dificultades para dormir?</p>
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
                    <div id="pregunta18" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_18" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha estado particularmente irritable o le han dado arranques de coraje?</p>
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
                    <div id="pregunta19" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_19" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha tenido dificultad para concentrarse?</p>
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
                    <div id="pregunta20" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_20" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Ha estado nervioso o constantemente en alerta?</p>
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
                    <div id="pregunta21" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp1_21" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            ¿Se ha sobresaltado fácilmente por cualquier cosa?</p>
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
                <button type="submit" class="btn btn-danger" id="guardar_guia1">
                    Guardar <i class="fa fa-save"></i>
                </button>
            </form>

        </div>


        <div id="guia2" class="card mt-4" style="display: block">
            <h6 style="text-align: center">Guía de referencia II</h6>
            <h3 class="card-title"><b>CUESTIONARIO PARA IDENTIFICAR LOS FACTORES DE RIESGO PSICOSOCIAL EN LOS CENTROS DE TRABAJO</b></h3>
            <form enctype="multipart/form-data" method="post" name="guia_2" id="guia_2">
                {!! csrf_field() !!}

                <div id="seccion1_2" class="mt-3" style="display: block; padding: 10px;">
                    <div id="pregunta1_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_1" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo me exige hacer mucho esfuerzo &nbsp;&nbsp; físico</p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta1_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta1_siempre" name="GUIA2_1" value="4">
                            </div>
                            <div>
                                <label for="preguta1_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta1_casi" name="GUIA2_1" value="3">
                            </div>
                            <div>
                                <label for="preguta1_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta1_algunas" name="GUIA2_1" value="2">
                            </div>
                            <div>
                                <label for="preguta1_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta1_casinunca" name="GUIA2_1" value="1">
                            </div>
                            <div>
                                <label for="preguta1_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta1_nunca" name="GUIA2_1" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta2_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_2" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me preocupa sufrir un accidente en mi 
                            trabajo </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta2_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta2_siempre" name="GUIA2_2" value="4">
                            </div>
                            <div>
                                <label for="preguta2_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta2_casi" name="GUIA2_2" value="3">
                            </div>
                            <div>
                                <label for="preguta2_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta2_algunas" name="GUIA2_2" value="2">
                            </div>
                            <div>
                                <label for="preguta2_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta2_casinunca" name="GUIA2_2" value="1">
                            </div>
                            <div>
                                <label for="preguta2_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta2_nunca" name="GUIA2_2" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta3_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_3" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que las actividades que realizo 
                            son peligrosas
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta3_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta3_siempre" name="GUIA2_3" value="4">
                            </div>
                            <div>
                                <label for="preguta3_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta3_casi" name="GUIA2_3" value="3">
                            </div>
                            <div>
                                <label for="preguta3_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta3_algunas" name="GUIA2_3" value="2">
                            </div>
                            <div>
                                <label for="preguta3_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta3_casinunca" name="GUIA2_3" value="1">
                            </div>
                            <div>
                                <label for="preguta3_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta3_nunca" name="GUIA2_3" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta4_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_4" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Por la cantidad de trabajo que tengo debo quedarme tiempo adicional a mi turno
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta4_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta4_siempre" name="GUIA2_4" value="4">
                            </div>
                            <div>
                                <label for="preguta4_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta4_casi" name="GUIA2_4" value="3">
                            </div>
                            <div>
                                <label for="preguta4_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta4_algunas" name="GUIA2_4" value="2">
                            </div>
                            <div>
                                <label for="preguta4_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta4_casinunca" name="GUIA2_4" value="1">
                            </div>
                            <div>
                                <label for="preguta4_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta4_nunca" name="GUIA2_4" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta5_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_5" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Por la cantidad de trabajo que tengo debo trabajar sin parar
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta5_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta5_siempre" name="GUIA2_5" value="4">
                            </div>
                            <div>
                                <label for="preguta5_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta5_casi" name="GUIA2_5" value="3">
                            </div>
                            <div>
                                <label for="preguta5_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta5_algunas" name="GUIA2_5" value="2">
                            </div>
                            <div>
                                <label for="preguta5_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta5_casinunca" name="GUIA2_5" value="1">
                            </div>
                            <div>
                                <label for="preguta5_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta5_nunca" name="GUIA2_5" value="0">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta6_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_6" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que es necesario mantener un <br> ritmo de trabajo acelerado
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta6_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta6_siempre" name="GUIA2_6" value="4">
                            </div>
                            <div>
                                <label for="preguta6_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta6_casi" name="GUIA2_6" value="3">
                            </div>
                            <div>
                                <label for="preguta6_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta6_algunas" name="GUIA2_6" value="2">
                            </div>
                            <div>
                                <label for="preguta6_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta6_casinunca" name="GUIA2_6" value="1">
                            </div>
                            <div>
                                <label for="preguta6_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta6_nunca" name="GUIA2_6" value="0">
                            </div>
                        </div>
                    </div>


                    <div id="pregunta7_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_7" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo exige que esté muy concentrado
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta7_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta7_siempre" name="GUIA2_7" value="4">
                            </div>
                            <div>
                                <label for="preguta7_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta7_casi" name="GUIA2_7" value="3">
                            </div>
                            <div>
                                <label for="preguta7_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta7_algunas" name="GUIA2_7" value="2">
                            </div>
                            <div>
                                <label for="preguta7_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta7_casinunca" name="GUIA2_7" value="1">
                            </div>
                            <div>
                                <label for="preguta7_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta7_nunca" name="GUIA2_7" value="0">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta8_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_8" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo requiere que memorice mucha información
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta8_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta8_siempre" name="GUIA2_8" value="4">
                            </div>
                            <div>
                                <label for="preguta8_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta8_casi" name="GUIA2_8" value="3">
                            </div>
                            <div>
                                <label for="preguta8_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta8_algunas" name="GUIA2_8" value="2">
                            </div>
                            <div>
                                <label for="preguta8_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta8_casinunca" name="GUIA2_8" value="1">
                            </div>
                            <div>
                                <label for="preguta8_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta8_nunca" name="GUIA2_8" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta9_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_9" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo exige que atienda varios asuntos <br>
                            al mismo tiempo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta9_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta9_siempre" name="GUIA2_9" value="4">
                            </div>
                            <div>
                                <label for="preguta9_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta9_casi" name="GUIA2_9" value="3">
                            </div>
                            <div>
                                <label for="preguta9_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta9_algunas" name="GUIA2_9" value="2">
                            </div>
                            <div>
                                <label for="preguta9_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta9_casinunca" name="GUIA2_9" value="1">
                            </div>
                            <div>
                                <label for="preguta9_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta9_nunca" name="GUIA2_9" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con las actividades que realiza en su trabajo y las responsabilidades que tiene.</h6>
                    </div>
                    <div id="pregunta10_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_10" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo soy responsable de cosas de mucho valor
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta10_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta10_siempre" name="GUIA2_10" value="4">
                            </div>
                            <div>
                                <label for="preguta10_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta10_casi" name="GUIA2_10" value="3">
                            </div>
                            <div>
                                <label for="preguta10_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta10_algunas" name="GUIA2_10" value="2">
                            </div>
                            <div>
                                <label for="preguta10_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta10_casinunca" name="GUIA2_10" value="1">
                            </div>
                            <div>
                                <label for="preguta10_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta10_nunca" name="GUIA2_10" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta11_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_11" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Respondo ante mi jefe por los resultados de  <br> toda mi área de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta11_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta11_siempre" name="GUIA2_11" value="4">
                            </div>
                            <div>
                                <label for="preguta11_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta11_casi" name="GUIA2_11" value="3">
                            </div>
                            <div>
                                <label for="preguta11_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta11_algunas" name="GUIA2_11" value="2">
                            </div>
                            <div>
                                <label for="preguta11_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta11_casinunca" name="GUIA2_11" value="1">
                            </div>
                            <div>
                                <label for="preguta11_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta11_nunca" name="GUIA2_11" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta12_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_12" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo me dan órdenes contradictorias
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta12_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta12_siempre" name="GUIA2_12" value="4">
                            </div>
                            <div>
                                <label for="preguta12_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta12_casi" name="GUIA2_12" value="3">
                            </div>
                            <div>
                                <label for="preguta12_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta12_algunas" name="GUIA2_12" value="2">
                            </div>
                            <div>
                                <label for="preguta12_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta12_casinunca" name="GUIA2_12" value="1">
                            </div>
                            <div>
                                <label for="preguta12_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta12_nunca" name="GUIA2_12" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta13_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_13" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que en mi trabajo me piden hacer cosas innecesarias
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta13_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta13_siempre" name="GUIA2_13" value="4">
                            </div>
                            <div>
                                <label for="preguta13_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta13_casi" name="GUIA2_13" value="3">
                            </div>
                            <div>
                                <label for="preguta13_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta13_algunas" name="GUIA2_13" value="2">
                            </div>
                            <div>
                                <label for="preguta13_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta13_casinunca" name="GUIA2_13" value="1">
                            </div>
                            <div>
                                <label for="preguta13_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta13_nunca" name="GUIA2_13" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con el tiempo destinado a su trabajo y sus responsabilidades familiares.</h6>
                    </div>
                    <div id="pregunta14_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_14" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Trabajo horas extras más de tres veces a la semana
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta14_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta14_siempre" name="GUIA2_14" value="4">
                            </div>
                            <div>
                                <label for="preguta14_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta14_casi" name="GUIA2_14" value="3">
                            </div>
                            <div>
                                <label for="preguta14_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta14_algunas" name="GUIA2_14" value="2">
                            </div>
                            <div>
                                <label for="preguta14_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta14_casinunca" name="GUIA2_14" value="1">
                            </div>
                            <div>
                                <label for="preguta14_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta14_nunca" name="GUIA2_14" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta15_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_15" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo me exige laborar en días de <br> descanso, festivos o fines de semana
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta15_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta15_siempre" name="GUIA2_15" value="4">
                            </div>
                            <div>
                                <label for="preguta15_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta15_casi" name="GUIA2_15" value="3">
                            </div>
                            <div>
                                <label for="preguta15_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta15_algunas" name="GUIA2_15" value="2">
                            </div>
                            <div>
                                <label for="preguta15_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta15_casinunca" name="GUIA2_15" value="1">
                            </div>
                            <div>
                                <label for="preguta15_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta15_nunca" name="GUIA2_15" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta16_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_16" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que el tiempo en el trabajo es <br> mucho y perjudica mis actividades familiares <br> o personales
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta16_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta16_siempre" name="GUIA2_16" value="4">
                            </div>
                            <div>
                                <label for="preguta16_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta16_casi" name="GUIA2_16" value="3">
                            </div>
                            <div>
                                <label for="preguta16_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta16_algunas" name="GUIA2_16" value="2">
                            </div>
                            <div>
                                <label for="preguta16_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta16_casinunca" name="GUIA2_16" value="1">
                            </div>
                            <div>
                                <label for="preguta16_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta16_nunca" name="GUIA2_16" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta17_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_17" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Pienso en las actividades familiares o <br> personales cuando estoy en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta17_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta17_siempre" name="GUIA2_17" value="4">
                            </div>
                            <div>
                                <label for="preguta17_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta17_casi" name="GUIA2_17" value="3">
                            </div>
                            <div>
                                <label for="preguta17_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta17_algunas" name="GUIA2_17" value="2">
                            </div>
                            <div>
                                <label for="preguta17_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta17_casinunca" name="GUIA2_17" value="1">
                            </div>
                            <div>
                                <label for="preguta17_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta17_nunca" name="GUIA2_17" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con las decisiones que puede tomar en su trabajo.
                        </h6>
                    </div>

                    <div id="pregunta18_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_18" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo permite que desarrolle nuevas habilidades
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta18_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta18_siempre" name="GUIA2_18" value="0">
                            </div>
                            <div>
                                <label for="preguta18_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta18_casi" name="GUIA2_18" value="1">
                            </div>
                            <div>
                                <label for="preguta18_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta18_algunas" name="GUIA2_18" value="2">
                            </div>
                            <div>
                                <label for="preguta18_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta18_casinunca" name="GUIA2_18" value="3">
                            </div>
                            <div>
                                <label for="preguta18_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta18_nunca" name="GUIA2_18" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta19_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_19" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo puedo aspirar a un mejor <br>
                            puesto
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta19_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta19_siempre" name="GUIA2_19" value="0">
                            </div>
                            <div>
                                <label for="preguta19_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta19_casi" name="GUIA2_19" value="1">
                            </div>
                            <div>
                                <label for="preguta19_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta19_algunas" name="GUIA2_19" value="2">
                            </div>
                            <div>
                                <label for="preguta19_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta19_casinunca" name="GUIA2_19" value="3">
                            </div>
                            <div>
                                <label for="preguta19_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta19_nunca" name="GUIA2_19" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta20_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_20" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Durante mi jornada de trabajo puedo tomar pausas cuando las necesito
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta20_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta20_siempre" name="GUIA2_20" value="0">
                            </div>
                            <div>
                                <label for="preguta20_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta20_casi" name="GUIA2_20" value="1">
                            </div>
                            <div>
                                <label for="preguta20_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta20_algunas" name="GUIA2_20" value="2">
                            </div>
                            <div>
                                <label for="preguta20_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta20_casinunca" name="GUIA2_20" value="3">
                            </div>
                            <div>
                                <label for="preguta20_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta20_nunca" name="GUIA2_20" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta21_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_21" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Puedo decidir la velocidad a la que realizo mis actividades en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta21_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta21_siempre" name="GUIA2_21" value="0">
                            </div>
                            <div>
                                <label for="preguta21_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta21_casi" name="GUIA2_21" value="1">
                            </div>
                            <div>
                                <label for="preguta21_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta21_algunas" name="GUIA2_21" value="2">
                            </div>
                            <div>
                                <label for="preguta21_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta21_casinunca" name="GUIA2_21" value="3">
                            </div>
                            <div>
                                <label for="preguta21_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta21_nunca" name="GUIA2_21" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta22_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_22" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Puedo cambiar el orden de las actividades <br> que realizo en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta22_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta22_siempre" name="GUIA2_22" value="0">
                            </div>
                            <div>
                                <label for="preguta22_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta22_casi" name="GUIA2_22" value="1">
                            </div>
                            <div>
                                <label for="preguta22_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta22_algunas" name="GUIA2_22" value="2">
                            </div>
                            <div>
                                <label for="preguta22_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta22_casinunca" name="GUIA2_22" value="3">
                            </div>
                            <div>
                                <label for="preguta22_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta22_nunca" name="GUIA2_22" value="4">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con la capacitación e información que recibe sobre su trabajo.
                        </h6>
                    </div>


                    <div id="pregunta23_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_23" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me informan con claridad cuáles son mis funciones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta23_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta23_siempre" name="GUIA2_23" value="0">
                            </div>
                            <div>
                                <label for="preguta23_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta23_casi" name="GUIA2_23" value="1">
                            </div>
                            <div>
                                <label for="preguta23_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta23_algunas" name="GUIA2_23" value="2">
                            </div>
                            <div>
                                <label for="preguta23_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta23_casinunca" name="GUIA2_23" value="3">
                            </div>
                            <div>
                                <label for="preguta23_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta23_nunca" name="GUIA2_23" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta24_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_24" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me explican claramente los resultados que <br> debo obtener en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta24_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta24_siempre" name="GUIA2_24" value="0">
                            </div>
                            <div>
                                <label for="preguta24_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta24_casi" name="GUIA2_24" value="1">
                            </div>
                            <div>
                                <label for="preguta24_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta24_algunas" name="GUIA2_24" value="2">
                            </div>
                            <div>
                                <label for="preguta24_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta24_casinunca" name="GUIA2_24" value="3">
                            </div>
                            <div>
                                <label for="preguta24_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta24_nunca" name="GUIA2_24" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta25_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_25" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me informan con quién puedo resolver problemas o asuntos de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta25_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta25_siempre" name="GUIA2_25" value="0">
                            </div>
                            <div>
                                <label for="preguta25_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta25_casi" name="GUIA2_25" value="1">
                            </div>
                            <div>
                                <label for="preguta25_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta25_algunas" name="GUIA2_25" value="2">
                            </div>
                            <div>
                                <label for="preguta25_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta25_casinunca" name="GUIA2_25" value="3">
                            </div>
                            <div>
                                <label for="preguta25_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta25_nunca" name="GUIA2_25" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta26_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_26" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me permiten asistir a capacitaciones relacionadas con mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta26_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta26_siempre" name="GUIA2_26" value="0">
                            </div>
                            <div>
                                <label for="preguta26_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta26_casi" name="GUIA2_26" value="1">
                            </div>
                            <div>
                                <label for="preguta26_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta26_algunas" name="GUIA2_26" value="2">
                            </div>
                            <div>
                                <label for="preguta26_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta26_casinunca" name="GUIA2_26" value="3">
                            </div>
                            <div>
                                <label for="preguta26_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta26_nunca" name="GUIA2_26" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta27_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_27" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Recibo capacitación útil para hacer mi <br>
                            trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta27_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta27_siempre" name="GUIA2_27" value="0">
                            </div>
                            <div>
                                <label for="preguta27_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta27_casi" name="GUIA2_27" value="1">
                            </div>
                            <div>
                                <label for="preguta27_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta27_algunas" name="GUIA2_27" value="2">
                            </div>
                            <div>
                                <label for="preguta27_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta27_casinunca" name="GUIA2_27" value="3">
                            </div>
                            <div>
                                <label for="preguta27_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta27_nunca" name="GUIA2_27" value="4">
                            </div>
                        </div>
                    </div>


                    <div class="mt-5">
                        <h6>Las preguntas siguientes se refieren a las relaciones con sus compañeros de trabajo y su jefe.
                        </h6>
                    </div>

                    <div id="pregunta28_2" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_28" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi jefe tiene en cuenta mis puntos de vista y opiniones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta28_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta28_siempre" name="GUIA2_28" value="0">
                            </div>
                            <div>
                                <label for="preguta28_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta28_casi" name="GUIA2_28" value="1">
                            </div>
                            <div>
                                <label for="preguta28_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta28_algunas" name="GUIA2_28" value="2">
                            </div>
                            <div>
                                <label for="preguta28_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta28_casinunca" name="GUIA2_28" value="3">
                            </div>
                            <div>
                                <label for="preguta28_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta28_nunca" name="GUIA2_28" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta29_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_29" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi jefe ayuda a solucionar los problemas que <br> se presentan en el trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta29_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta29_siempre" name="GUIA2_29" value="0">
                            </div>
                            <div>
                                <label for="preguta29_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta29_casi" name="GUIA2_29" value="1">
                            </div>
                            <div>
                                <label for="preguta29_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta29_algunas" name="GUIA2_29" value="2">
                            </div>
                            <div>
                                <label for="preguta29_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta29_casinunca" name="GUIA2_29" value="3">
                            </div>
                            <div>
                                <label for="preguta29_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta29_nunca" name="GUIA2_29" value="4">
                            </div>
                        </div>
                    </div>


                    <div id="pregunta30_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_30" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Puedo confiar en mis compañeros de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta30_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta30_siempre" name="GUIA2_30" value="0">
                            </div>
                            <div>
                                <label for="preguta30_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta30_casi" name="GUIA2_30" value="1">
                            </div>
                            <div>
                                <label for="preguta30_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta30_algunas" name="GUIA2_30" value="2">
                            </div>
                            <div>
                                <label for="preguta30_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta30_casinunca" name="GUIA2_30" value="3">
                            </div>
                            <div>
                                <label for="preguta30_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta30_nunca" name="GUIA2_30" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta31_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_31" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Cuando tenemos que realizar trabajo de <br> equipo los compañeros colaboran
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta31_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta31_siempre" name="GUIA2_31" value="0">
                            </div>
                            <div>
                                <label for="preguta31_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta31_casi" name="GUIA2_31" value="1">
                            </div>
                            <div>
                                <label for="preguta31_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta31_algunas" name="GUIA2_31" value="2">
                            </div>
                            <div>
                                <label for="preguta31_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta31_casinunca" name="GUIA2_31" value="3">
                            </div>
                            <div>
                                <label for="preguta31_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta31_nunca" name="GUIA2_31" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta32_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_32" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mis compañeros de trabajo me ayudan <br> cuando tengo dificultades
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta32_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta32_siempre" name="GUIA2_32" value="0">
                            </div>
                            <div>
                                <label for="preguta32_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta32_casi" name="GUIA2_32" value="1">
                            </div>
                            <div>
                                <label for="preguta32_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta32_algunas" name="GUIA2_32" value="2">
                            </div>
                            <div>
                                <label for="preguta32_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta32_casinunca" name="GUIA2_32" value="3">
                            </div>
                            <div>
                                <label for="preguta32_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta32_nunca" name="GUIA2_32" value="4">
                            </div>
                        </div>
                    </div>


                    <div id="pregunta33_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_33" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo puedo expresarme libremente <br> sin interrupciones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta33_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta33_siempre" name="GUIA2_33" value="0">
                            </div>
                            <div>
                                <label for="preguta33_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta33_casi" name="GUIA2_33" value="1">
                            </div>
                            <div>
                                <label for="preguta33_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta33_algunas" name="GUIA2_33" value="2">
                            </div>
                            <div>
                                <label for="preguta33_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta33_casinunca" name="GUIA2_33" value="3">
                            </div>
                            <div>
                                <label for="preguta33_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta33_nunca" name="GUIA2_33" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta34_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_34" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Recibo críticas constantes a mi persona y/o trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta34_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta34_siempre" name="GUIA2_34" value="4">
                            </div>
                            <div>
                                <label for="preguta34_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta34_casi" name="GUIA2_34" value="3">
                            </div>
                            <div>
                                <label for="preguta34_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta34_algunas" name="GUIA2_34" value="2">
                            </div>
                            <div>
                                <label for="preguta34_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta34_casinunca" name="GUIA2_34" value="1">
                            </div>
                            <div>
                                <label for="preguta34_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta34_nunca" name="GUIA2_34" value="0">
                            </div>
                        </div>
                    </div>


                    <div id="pregunta35_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_35" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Recibo burlas, calumnias, difamaciones, humillaciones o ridiculizaciones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta35_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta35_siempre" name="GUIA2_35" value="4">
                            </div>
                            <div>
                                <label for="preguta35_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta35_casi" name="GUIA2_35" value="3">
                            </div>
                            <div>
                                <label for="preguta35_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta35_algunas" name="GUIA2_35" value="2">
                            </div>
                            <div>
                                <label for="preguta35_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta35_casinunca" name="GUIA2_35" value="1">
                            </div>
                            <div>
                                <label for="preguta35_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta35_nunca" name="GUIA2_35" value="0">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta36_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_36" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Se ignora mi presencia o se me excluye de <br> las reuniones de trabajo y en la toma de decisiones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta36_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta36_siempre" name="GUIA2_36" value="4">
                            </div>
                            <div>
                                <label for="preguta36_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta36_casi" name="GUIA2_36" value="3">
                            </div>
                            <div>
                                <label for="preguta36_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta36_algunas" name="GUIA2_36" value="2">
                            </div>
                            <div>
                                <label for="preguta36_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta36_casinunca" name="GUIA2_36" value="1">
                            </div>
                            <div>
                                <label for="preguta36_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta36_nunca" name="GUIA2_36" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta37_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_37" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Se manipulan las situaciones de trabajo <br> para hacerme parecer un mal trabajador
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta37_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta37_siempre" name="GUIA2_37" value="4">
                            </div>
                            <div>
                                <label for="preguta37_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta37_casi" name="GUIA2_37" value="3">
                            </div>
                            <div>
                                <label for="preguta37_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta37_algunas" name="GUIA2_37" value="2">
                            </div>
                            <div>
                                <label for="preguta37_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta37_casinunca" name="GUIA2_37" value="1">
                            </div>
                            <div>
                                <label for="preguta37_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta37_nunca" name="GUIA2_37" value="0">
                            </div>
                        </div>
                    </div>


                    <div id="pregunta38_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_38" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Se ignoran mis éxitos laborales y se atribuyen <br> a otros trabajadores
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta38_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta38_siempre" name="GUIA2_38" value="4">
                            </div>
                            <div>
                                <label for="preguta38_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta38_casi" name="GUIA2_38" value="3">
                            </div>
                            <div>
                                <label for="preguta38_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta38_algunas" name="GUIA2_38" value="2">
                            </div>
                            <div>
                                <label for="preguta38_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta38_casinunca" name="GUIA2_38" value="1">
                            </div>
                            <div>
                                <label for="preguta38_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta38_nunca" name="GUIA2_38" value="0">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta39_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_39" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me bloquean o impiden las oportunidades <br> que tengo para obtener ascenso o mejora <br>en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta39_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta39_siempre" name="GUIA2_39" value="4">
                            </div>
                            <div>
                                <label for="preguta39_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta39_casi" name="GUIA2_39" value="3">
                            </div>
                            <div>
                                <label for="preguta39_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta39_algunas" name="GUIA2_39" value="2">
                            </div>
                            <div>
                                <label for="preguta39_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta39_casinunca" name="GUIA2_39" value="1">
                            </div>
                            <div>
                                <label for="preguta39_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta39_nunca" name="GUIA2_39" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta40_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_40" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            He presenciado actos de violencias en mi <br> centro de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta40_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta40_siempre" name="GUIA2_40" value="4">
                            </div>
                            <div>
                                <label for="preguta40_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta40_casi" name="GUIA2_40" value="3">
                            </div>
                            <div>
                                <label for="preguta40_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta40_algunas" name="GUIA2_40" value="2">
                            </div>
                            <div>
                                <label for="preguta40_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta40_casinunca" name="GUIA2_40" value="1">
                            </div>
                            <div>
                                <label for="preguta40_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta40_nunca" name="GUIA2_40" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con la atención a clientes y usuarios.
                        </h6>
                    </div>

                    <div id="preguntaadi1_2" class="mt-5" style="display: flex; align-items: center; margin-bottom: 10px;">
                        <p style="margin: 0; white-space: nowrap; margin-right: 10px;">
                            En mi trabajo debo brindar servicio a clientes o usuarios:
                        </p>
                        <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                            <div style="display: inline-block;">
                                <label for="preguntaadi1_si" style="line-height: 1; margin-right: 5px;">Sí</label>
                                <input type="radio" id="preguntaadi1_si" name="GUIAE2_1" value="1" style="vertical-align: middle;" required onchange="clientesyusuarios()">
                            </div>
                            <div style="display: inline-block;">
                                <label for="preguntaadi1_no" style="line-height: 1; margin-right: 5px;">No</label>
                                <input type="radio" id="preguntaadi1_no" name="GUIAE2_1" value="0" style="vertical-align: middle;" onchange="clientesyusuarios()">
                            </div>
                        </div>
                    </div>


                    <div class="mt-3">
                        <h6>Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO" pase a las preguntas de la sección siguiente.
                        </h6>
                    </div>

                </div>


                <div id="seccion2_2" class="mt-2" style="display: none; padding: 10px;">

                    <div id="pregunta41_2" class="mt-3" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_41" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Atiendo clientes o usuarios muy enojados
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta41_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta41_siempre" name="GUIA2_41" value="4">
                            </div>
                            <div>
                                <label for="preguta41_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta41_casi" name="GUIA2_41" value="3">
                            </div>
                            <div>
                                <label for="preguta41_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta41_algunas" name="GUIA2_41" value="2">
                            </div>
                            <div>
                                <label for="preguta41_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta41_casinunca" name="GUIA2_41" value="1">
                            </div>
                            <div>
                                <label for="preguta41_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta41_nunca" name="GUIA2_41" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta42_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_42" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo me exige atender personas muy necesitadas de ayuda o enfermas
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta42_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta42_siempre" name="GUIA2_42" value="4">
                            </div>
                            <div>
                                <label for="preguta42_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta42_casi" name="GUIA2_42" value="3">
                            </div>
                            <div>
                                <label for="preguta42_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta42_algunas" name="GUIA2_42" value="2">
                            </div>
                            <div>
                                <label for="preguta42_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta42_casinunca" name="GUIA2_42" value="1">
                            </div>
                            <div>
                                <label for="preguta42_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta42_nunca" name="GUIA2_42" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta43_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_43" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Para hacer mi trabajo debo demostrar sentimientos distintos a los míos
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta43_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta43_siempre" name="GUIA2_43" value="4">
                            </div>
                            <div>
                                <label for="preguta43_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta43_casi" name="GUIA2_43" value="3">
                            </div>
                            <div>
                                <label for="preguta43_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta43_algunas" name="GUIA2_43" value="2">
                            </div>
                            <div>
                                <label for="preguta43_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta43_casinunca" name="GUIA2_43" value="1">
                            </div>
                            <div>
                                <label for="preguta43_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta43_nunca" name="GUIA2_43" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="seccion3_2" class="mt-2" style="display: block; padding: 10px;">
                    <div id="preguntaadi2_2" class="mt-2" style="display: flex; align-items: center; margin-bottom: 10px;">
                        <p style="margin: 0; white-space: nowrap; margin-right: 10px;">
                            Soy jefe de otros trabajadores:
                        </p>
                        <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                            <div style="display: inline-block;">
                                <label for="preguntaadi2_si" style="line-height: 1; margin-right: 5px;">Sí</label>
                                <input type="radio" id="preguntaadi2_si" name="GUIAE2_2" value="1" style="vertical-align: middle;" required onchange="jefetrabajadores()">
                            </div>
                            <div style="display: inline-block;">
                                <label for="preguntaadi2_no" style="line-height: 1; margin-right: 5px;">No</label>
                                <input type="radio" id="preguntaadi2_no" name="GUIAE2_2" value="0" style="vertical-align: middle;" onchange="jefetrabajadores()">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO", ha concluido el cuestionario.
                        </h6>
                    </div>
                </div>
                <div id="seccion4_2" class="mt-2" style="display: none; padding: 10px;">
                    <div class="mt-3">
                        <h6>Las siguientes preguntas están relacionadas con las actitudes de los trabajadores que supervisa.
                        </h6>
                    </div>
                    <div id="pregunta44_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_44" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Comunican tarde los asuntos de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta44_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta44_siempre" name="GUIA2_44" value="4">
                            </div>
                            <div>
                                <label for="preguta44_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta44_casi" name="GUIA2_44" value="3">
                            </div>
                            <div>
                                <label for="preguta44_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta44_algunas" name="GUIA2_44" value="2">
                            </div>
                            <div>
                                <label for="preguta44_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta44_casinunca" name="GUIA2_44" value="1">
                            </div>
                            <div>
                                <label for="preguta44_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta44_nunca" name="GUIA2_44" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta45_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_45" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Dificultan el logro de los resultados del <br>
                            trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta45_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta45_siempre" name="GUIA2_45" value="4">
                            </div>
                            <div>
                                <label for="preguta45_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta45_casi" name="GUIA2_45" value="3">
                            </div>
                            <div>
                                <label for="preguta45_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta45_algunas" name="GUIA2_45" value="2">
                            </div>
                            <div>
                                <label for="preguta45_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta45_casinunca" name="GUIA2_45" value="1">
                            </div>
                            <div>
                                <label for="preguta45_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta45_nunca" name="GUIA2_45" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta46_2" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp2_46" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Ignoran las sugerencias para mejorar <br> su trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta46_siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta46_siempre" name="GUIA2_46" value="4">
                            </div>
                            <div>
                                <label for="preguta46_casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta46_casi" name="GUIA2_46" value="3">
                            </div>
                            <div>
                                <label for="preguta46_algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta46_algunas" name="GUIA2_46" value="2">
                            </div>
                            <div>
                                <label for="preguta46_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta46_casinunca" name="GUIA2_46" value="1">
                            </div>
                            <div>
                                <label for="preguta46_nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta46_nunca" name="GUIA2_46" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger" id="guardar_guia2">
                    Guardar <i class="fa fa-save"></i>
                </button>
            </form>
        </div>








        <div id="guia3" class="card mt-4" style="display: block">
            <h6 style="text-align: center">Guía de referencia III</h6>
            <h3 class="card-title"><b>CUESTIONARIO PARA IDENTIFICAR LOS FACTORES DE RIESGO PSICOSOCIAL Y EVALUAR EL ENTORNO ORGANIZACIONAL EN LOS CENTROS DE TRABAJO</b></h3>
            <form enctype="multipart/form-data" method="post" name="guia_3" id="guia_3">
                {!! csrf_field() !!}

                <div class="mt-3">
                    <h6>Para responder las siguientes preguntas considere las condiciones ambientales de su centro de trabajo.
                    </h6>
                </div>

                <div id="seccion1_3" class="mt-2" style="display: block; padding: 10px;">
                    <div id="pregunta1_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_1" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            El espacio donde trabajo me permite realizar <br> mis actividades de manera segura e higiénica
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta1_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta1_3siempre" name="GUIA3_1" value="0">
                            </div>
                            <div>
                                <label for="preguta1_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta1_3casi" name="GUIA3_1" value="1">
                            </div>
                            <div>
                                <label for="preguta1_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta1_3algunas" name="GUIA3_1" value="2">
                            </div>
                            <div>
                                <label for="preguta1_casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta1_3casinunca" name="GUIA3_1" value="3">
                            </div>
                            <div>
                                <label for="preguta1_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta1_3nunca" name="GUIA3_1" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta2_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_2" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo me exige hacer muchos esfuerzo <br> físico
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta2_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta2_3siempre" name="GUIA3_2" value="4">
                            </div>
                            <div>
                                <label for="preguta2_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta2_3casi" name="GUIA3_2" value="3">
                            </div>
                            <div>
                                <label for="preguta2_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta2_3algunas" name="GUIA3_2" value="2">
                            </div>
                            <div>
                                <label for="preguta2_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta2_3casinunca" name="GUIA3_2" value="1">
                            </div>
                            <div>
                                <label for="preguta2_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta2_3nunca" name="GUIA3_2" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta3_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_3" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me preocupa sufrir un accidente en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta3_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta3_3siempre" name="GUIA3_3" value="4">
                            </div>
                            <div>
                                <label for="preguta3_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta3_3casi" name="GUIA3_3" value="3">
                            </div>
                            <div>
                                <label for="preguta3_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta3_3algunas" name="GUIA3_3" value="2">
                            </div>
                            <div>
                                <label for="preguta3_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta3_3casinunca" name="GUIA3_3" value="1">
                            </div>
                            <div>
                                <label for="preguta3_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta3_3nunca" name="GUIA3_3" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta4_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_4" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que en mi trabajo se aplican las normas de seguridad y salud en el trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta4_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta4_3siempre" name="GUIA3_4" value="0">
                            </div>
                            <div>
                                <label for="preguta4_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta4_3casi" name="GUIA3_4" value="1">
                            </div>
                            <div>
                                <label for="preguta4_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta4_3algunas" name="GUIA3_4" value="2">
                            </div>
                            <div>
                                <label for="preguta4_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta4_3casinunca" name="GUIA3_4" value="3">
                            </div>
                            <div>
                                <label for="preguta4_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta4_3nunca" name="GUIA3_4" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta5_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_5" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que las actividades que realizo son peligrosas
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta5_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta5_3siempre" name="GUIA3_5" value="4">
                            </div>
                            <div>
                                <label for="preguta5_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta5_3casi" name="GUIA3_5" value="3">
                            </div>
                            <div>
                                <label for="preguta5_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta5_3algunas" name="GUIA3_5" value="2">
                            </div>
                            <div>
                                <label for="preguta5_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta5_3casinunca" name="GUIA3_5" value="1">
                            </div>
                            <div>
                                <label for="preguta5_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta5_3nunca" name="GUIA3_5" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Para responder a las preguntas siguientes piense en la cantidad y ritmo de trabajo que tiene.
                        </h6>
                    </div>
                    <div id="pregunta6_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_6" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Por la cantidad de trabajo que tengo debo quedarme tiempo adicional a mi turno
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta6_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta6_3siempre" name="GUIA3_6" value="4">
                            </div>
                            <div>
                                <label for="preguta6_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta6_3casi" name="GUIA3_6" value="3">
                            </div>
                            <div>
                                <label for="preguta6_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta6_3algunas" name="GUIA3_6" value="2">
                            </div>
                            <div>
                                <label for="preguta6_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta6_3casinunca" name="GUIA3_6" value="1">
                            </div>
                            <div>
                                <label for="preguta6_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta6_3nunca" name="GUIA3_6" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta7_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_7" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Por la cantidad de trabajo que tengo debo trabajar sin parar
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta7_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta7_3siempre" name="GUIA3_7" value="4">
                            </div>
                            <div>
                                <label for="preguta7_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta7_3casi" name="GUIA3_7" value="3">
                            </div>
                            <div>
                                <label for="preguta7_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta7_3algunas" name="GUIA3_7" value="2">
                            </div>
                            <div>
                                <label for="preguta7_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta7_3casinunca" name="GUIA3_7" value="1">
                            </div>
                            <div>
                                <label for="preguta7_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta7_3nunca" name="GUIA3_7" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta8_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_8" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que es necesario mantener un <br> ritmo de trabajo acelerado
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta8_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta8_3siempre" name="GUIA3_8" value="4">
                            </div>
                            <div>
                                <label for="preguta8_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta8_3casi" name="GUIA3_8" value="3">
                            </div>
                            <div>
                                <label for="preguta8_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta8_3algunas" name="GUIA3_8" value="2">
                            </div>
                            <div>
                                <label for="preguta8_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta8_3casinunca" name="GUIA3_8" value="1">
                            </div>
                            <div>
                                <label for="preguta8_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta8_3nunca" name="GUIA3_8" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con el esfuerzo mental que le exige su trabajo.
                        </h6>
                    </div>
                    <div id="pregunta9_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_9" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo exige que esté muy concentrado
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta9_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta9_3siempre" name="GUIA3_9" value="4">
                            </div>
                            <div>
                                <label for="preguta9_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta9_3casi" name="GUIA3_9" value="3">
                            </div>
                            <div>
                                <label for="preguta9_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta9_3algunas" name="GUIA3_9" value="2">
                            </div>
                            <div>
                                <label for="preguta9_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta9_3casinunca" name="GUIA3_9" value="1">
                            </div>
                            <div>
                                <label for="preguta9_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta9_3nunca" name="GUIA3_9" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta10_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_10" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo requiere que memorice mucha información
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta10_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta10_3siempre" name="GUIA3_10" value="4">
                            </div>
                            <div>
                                <label for="preguta10_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta10_3casi" name="GUIA3_10" value="3">
                            </div>
                            <div>
                                <label for="preguta10_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta10_3algunas" name="GUIA3_10" value="2">
                            </div>
                            <div>
                                <label for="preguta10_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta10_3casinunca" name="GUIA3_10" value="1">
                            </div>
                            <div>
                                <label for="preguta10_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta10_3nunca" name="GUIA3_10" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta11_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_11" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo tengo que tomar decisiones difíciles muy rápido
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta11_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta11_3siempre" name="GUIA3_11" value="4">
                            </div>
                            <div>
                                <label for="preguta11_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta11_3casi" name="GUIA3_11" value="3">
                            </div>
                            <div>
                                <label for="preguta11_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta11_3algunas" name="GUIA3_11" value="2">
                            </div>
                            <div>
                                <label for="preguta11_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta11_3casinunca" name="GUIA3_11" value="1">
                            </div>
                            <div>
                                <label for="preguta11_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta11_3nunca" name="GUIA3_11" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta12_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_12" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo exige que atienda varios asuntos
                            <br> al mismo tiempo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta12_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta12_3siempre" name="GUIA3_12" value="4">
                            </div>
                            <div>
                                <label for="preguta12_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta12_3casi" name="GUIA3_12" value="3">
                            </div>
                            <div>
                                <label for="preguta12_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta12_3algunas" name="GUIA3_12" value="2">
                            </div>
                            <div>
                                <label for="preguta12_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta12_3casinunca" name="GUIA3_12" value="1">
                            </div>
                            <div>
                                <label for="preguta12_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta12_3nunca" name="GUIA3_12" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con las actividades que realiza en su trabajo y las responsabilidades que tiene.
                        </h6>
                    </div>
                    <div id="pregunta13_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_13" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo soy responsable de cosas de mucho valor
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta13_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta13_3siempre" name="GUIA3_13" value="4">
                            </div>
                            <div>
                                <label for="preguta13_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta13_3casi" name="GUIA3_13" value="3">
                            </div>
                            <div>
                                <label for="preguta13_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta13_3algunas" name="GUIA3_13" value="2">
                            </div>
                            <div>
                                <label for="preguta13_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta13_3casinunca" name="GUIA3_13" value="1">
                            </div>
                            <div>
                                <label for="preguta13_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta13_3nunca" name="GUIA3_13" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta14_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_14" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Respondo ante mi jefe por los resultados de <br> toda mi área de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta14_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta14_3siempre" name="GUIA3_14" value="4">
                            </div>
                            <div>
                                <label for="preguta14_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta14_3casi" name="GUIA3_14" value="3">
                            </div>
                            <div>
                                <label for="preguta14_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta14_3algunas" name="GUIA3_14" value="2">
                            </div>
                            <div>
                                <label for="preguta14_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta14_3casinunca" name="GUIA3_14" value="1">
                            </div>
                            <div>
                                <label for="preguta14_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta14_3nunca" name="GUIA3_14" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta15_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_15" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En el trabajo me dan órdenes contradictorias
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta15_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta15_3siempre" name="GUIA3_15" value="4">
                            </div>
                            <div>
                                <label for="preguta15_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta15_3casi" name="GUIA3_15" value="3">
                            </div>
                            <div>
                                <label for="preguta15_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta15_3algunas" name="GUIA3_15" value="2">
                            </div>
                            <div>
                                <label for="preguta15_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta15_3casinunca" name="GUIA3_15" value="1">
                            </div>
                            <div>
                                <label for="preguta15_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta15_3nunca" name="GUIA3_15" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta16_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_16" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que en mi trabajo me piden hacer cosas innecesarias
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta16_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta16_3siempre" name="GUIA3_16" value="4">
                            </div>
                            <div>
                                <label for="preguta16_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta16_3casi" name="GUIA3_16" value="3">
                            </div>
                            <div>
                                <label for="preguta16_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta16_3algunas" name="GUIA3_16" value="2">
                            </div>
                            <div>
                                <label for="preguta16_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta16_3casinunca" name="GUIA3_16" value="1">
                            </div>
                            <div>
                                <label for="preguta16_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta16_3nunca" name="GUIA3_16" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con su jornada de trabajo.
                        </h6>
                    </div>
                    <div id="pregunta17_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_17" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Trabajo horas extras más de tres veces a la semana
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta17_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta17_3siempre" name="GUIA3_17" value="4">
                            </div>
                            <div>
                                <label for="preguta17_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta17_3casi" name="GUIA3_17" value="3">
                            </div>
                            <div>
                                <label for="preguta17_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta17_3algunas" name="GUIA3_17" value="2">
                            </div>
                            <div>
                                <label for="preguta17_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta17_3casinunca" name="GUIA3_17" value="1">
                            </div>
                            <div>
                                <label for="preguta17_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta17_3nunca" name="GUIA3_17" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta18_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_18" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo me exige laborar en días de <br> descanso, festivos o fines de semana
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta18_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta18_3siempre" name="GUIA3_18" value="4">
                            </div>
                            <div>
                                <label for="preguta18_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta18_3casi" name="GUIA3_18" value="3">
                            </div>
                            <div>
                                <label for="preguta18_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta18_3algunas" name="GUIA3_18" value="2">
                            </div>
                            <div>
                                <label for="preguta18_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta18_3casinunca" name="GUIA3_18" value="1">
                            </div>
                            <div>
                                <label for="preguta18_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta18_3nunca" name="GUIA3_18" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta19_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_19" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que el tiempo en el trabajo es <br> mucho y perjudica mis actividades familiares <br> o personales
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta19_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta19_3siempre" name="GUIA3_19" value="4">
                            </div>
                            <div>
                                <label for="preguta19_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta19_3casi" name="GUIA3_19" value="3">
                            </div>
                            <div>
                                <label for="preguta19_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta19_3algunas" name="GUIA3_19" value="2">
                            </div>
                            <div>
                                <label for="preguta19_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta19_3casinunca" name="GUIA3_19" value="1">
                            </div>
                            <div>
                                <label for="preguta19_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta19_3nunca" name="GUIA3_19" value="0">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta20_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_20" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Debo atender asuntos de trabajo cunado <br> estoy en casa
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta20_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta20_3siempre" name="GUIA3_20" value="4">
                            </div>
                            <div>
                                <label for="preguta20_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta20_3casi" name="GUIA3_20" value="3">
                            </div>
                            <div>
                                <label for="preguta20_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta20_3algunas" name="GUIA3_20" value="2">
                            </div>
                            <div>
                                <label for="preguta20_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta20_3casinunca" name="GUIA3_20" value="1">
                            </div>
                            <div>
                                <label for="preguta20_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta20_3nunca" name="GUIA3_20" value="0">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta21_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_21" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Pienso en las actividades familiares o <br> personales cuando estoy en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta21_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta21_3siempre" name="GUIA3_21" value="4">
                            </div>
                            <div>
                                <label for="preguta21_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta21_3casi" name="GUIA3_21" value="3">
                            </div>
                            <div>
                                <label for="preguta21_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta21_3algunas" name="GUIA3_21" value="2">
                            </div>
                            <div>
                                <label for="preguta21_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta21_3casinunca" name="GUIA3_21" value="1">
                            </div>
                            <div>
                                <label for="preguta21_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta21_3nunca" name="GUIA3_21" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta22_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_22" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Pienso que mis responsabilidades familiares afectan mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta22_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta22_3siempre" name="GUIA3_22" value="4">
                            </div>
                            <div>
                                <label for="preguta22_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta22_3casi" name="GUIA3_22" value="3">
                            </div>
                            <div>
                                <label for="preguta22_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta22_3algunas" name="GUIA3_22" value="2">
                            </div>
                            <div>
                                <label for="preguta22_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta22_3casinunca" name="GUIA3_22" value="1">
                            </div>
                            <div>
                                <label for="preguta22_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta22_3nunca" name="GUIA3_22" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con las decisiones que puede tomar en su trabajo.
                        </h6>
                    </div>
                    <div id="pregunta23_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_23" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi trabajo permite que desarrolle nuevas habilidades
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta23_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta23_3siempre" name="GUIA3_23" value="0">
                            </div>
                            <div>
                                <label for="preguta23_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta23_3casi" name="GUIA3_23" value="1">
                            </div>
                            <div>
                                <label for="preguta23_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta23_3algunas" name="GUIA3_23" value="2">
                            </div>
                            <div>
                                <label for="preguta23_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta23_3casinunca" name="GUIA3_23" value="3">
                            </div>
                            <div>
                                <label for="preguta23_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta23_3nunca" name="GUIA3_23" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta24_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_24" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo puedo aspirar a un mejor <br>
                            puesto
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta24_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta24_3siempre" name="GUIA3_24" value="0">
                            </div>
                            <div>
                                <label for="preguta24_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta24_3casi" name="GUIA3_24" value="1">
                            </div>
                            <div>
                                <label for="preguta24_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta24_3algunas" name="GUIA3_24" value="2">
                            </div>
                            <div>
                                <label for="preguta24_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta24_3casinunca" name="GUIA3_24" value="3">
                            </div>
                            <div>
                                <label for="preguta24_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta24_3nunca" name="GUIA3_24" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta25_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_25" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Durante mi jornada de trabajo puedo tomar pausas cuando las necesito
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta25_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta25_3siempre" name="GUIA3_25" value="0">
                            </div>
                            <div>
                                <label for="preguta25_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta25_3casi" name="GUIA3_25" value="1">
                            </div>
                            <div>
                                <label for="preguta25_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta25_3algunas" name="GUIA3_25" value="2">
                            </div>
                            <div>
                                <label for="preguta25_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta25_3casinunca" name="GUIA3_25" value="3">
                            </div>
                            <div>
                                <label for="preguta25_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta25_3nunca" name="GUIA3_25" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta26_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_26" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Puedo decidir cuánto trabajo realizo durante <br> la jornada laboral
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta26_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta26_3siempre" name="GUIA3_26" value="0">
                            </div>
                            <div>
                                <label for="preguta26_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta26_3casi" name="GUIA3_26" value="1">
                            </div>
                            <div>
                                <label for="preguta26_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta26_3algunas" name="GUIA3_26" value="2">
                            </div>
                            <div>
                                <label for="preguta26_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta26_3casinunca" name="GUIA3_26" value="3">
                            </div>
                            <div>
                                <label for="preguta26_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta26_3nunca" name="GUIA3_26" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta27_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_27" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Puedo decidir la velocidad a la que realizo <br> mis actividades en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta27_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta27_3siempre" name="GUIA3_27" value="0">
                            </div>
                            <div>
                                <label for="preguta27_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta27_3casi" name="GUIA3_27" value="1">
                            </div>
                            <div>
                                <label for="preguta27_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta27_3algunas" name="GUIA3_27" value="2">
                            </div>
                            <div>
                                <label for="preguta27_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta27_3casinunca" name="GUIA3_27" value="3">
                            </div>
                            <div>
                                <label for="preguta27_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta27_3nunca" name="GUIA3_27" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta28_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_28" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Puedo cambiar el orden de las actividades <br> que realizo en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta28_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta28_3siempre" name="GUIA3_28" value="0">
                            </div>
                            <div>
                                <label for="preguta28_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta28_3casi" name="GUIA3_28" value="1">
                            </div>
                            <div>
                                <label for="preguta28_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta28_3algunas" name="GUIA3_28" value="2">
                            </div>
                            <div>
                                <label for="preguta28_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta28_3casinunca" name="GUIA3_28" value="3">
                            </div>
                            <div>
                                <label for="preguta28_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta28_3nunca" name="GUIA3_28" value="4">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con cualquier tipo de cambio que ocurra en su trabajo (considere los últimos cambios realizados).
                        </h6>
                    </div>
                    <div id="pregunta29_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_29" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Los cambios que se presentan en mi trabajo dificultan mi labor
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta29_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta29_3siempre" name="GUIA3_29" value="4">
                            </div>
                            <div>
                                <label for="preguta29_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta29_3casi" name="GUIA3_29" value="3">
                            </div>
                            <div>
                                <label for="preguta29_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta29_3algunas" name="GUIA3_29" value="2">
                            </div>
                            <div>
                                <label for="preguta29_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta29_3casinunca" name="GUIA3_29" value="1">
                            </div>
                            <div>
                                <label for="preguta29_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta29_3nunca" name="GUIA3_29" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta30_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_30" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Cuando se presentan cambios en mi <br> trabajo se tienen en cuenta mis ideas o aportaciones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta30_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta30_3siempre" name="GUIA3_30" value="0">
                            </div>
                            <div>
                                <label for="preguta30_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta30_3casi" name="GUIA3_30" value="1">
                            </div>
                            <div>
                                <label for="preguta30_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta30_3algunas" name="GUIA3_30" value="2">
                            </div>
                            <div>
                                <label for="preguta30_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta30_3casinunca" name="GUIA3_30" value="3">
                            </div>
                            <div>
                                <label for="preguta30_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta30_3nunca" name="GUIA3_30" value="4">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con la capacitación e información que se le proporciona sobre su trabajo.
                        </h6>
                    </div>

                    <div id="pregunta31_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_31" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me informan con claridad cuáles son mis funciones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta31_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta31_3siempre" name="GUIA3_31" value="0">
                            </div>
                            <div>
                                <label for="preguta31_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta31_3casi" name="GUIA3_31" value="1">
                            </div>
                            <div>
                                <label for="preguta31_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta31_3algunas" name="GUIA3_31" value="2">
                            </div>
                            <div>
                                <label for="preguta31_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta31_3casinunca" name="GUIA3_31" value="3">
                            </div>
                            <div>
                                <label for="preguta31_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta31_3nunca" name="GUIA3_31" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta32_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_32" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me explican claramente los resultados que <br> debo obtener en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta32_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta32_3siempre" name="GUIA3_32" value="0">
                            </div>
                            <div>
                                <label for="preguta32_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta32_3casi" name="GUIA3_32" value="1">
                            </div>
                            <div>
                                <label for="preguta32_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta32_3algunas" name="GUIA3_32" value="2">
                            </div>
                            <div>
                                <label for="preguta32_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta32_3casinunca" name="GUIA3_32" value="3">
                            </div>
                            <div>
                                <label for="preguta32_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta32_3nunca" name="GUIA3_32" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta33_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_33" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me explican claramente los objetivos de mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta33_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta33_3siempre" name="GUIA3_33" value="0">
                            </div>
                            <div>
                                <label for="preguta33_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta33_3casi" name="GUIA3_33" value="1">
                            </div>
                            <div>
                                <label for="preguta33_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta33_3algunas" name="GUIA3_33" value="2">
                            </div>
                            <div>
                                <label for="preguta33_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta33_3casinunca" name="GUIA3_33" value="3">
                            </div>
                            <div>
                                <label for="preguta33_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta33_3nunca" name="GUIA3_33" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta34_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_34" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me informan con quién puedo resolver problemas o asuntos de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta34_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta34_3siempre" name="GUIA3_34" value="0">
                            </div>
                            <div>
                                <label for="preguta34_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta34_3casi" name="GUIA3_34" value="1">
                            </div>
                            <div>
                                <label for="preguta34_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta34_3algunas" name="GUIA3_34" value="2">
                            </div>
                            <div>
                                <label for="preguta34_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta34_3casinunca" name="GUIA3_34" value="3">
                            </div>
                            <div>
                                <label for="preguta34_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta34_3nunca" name="GUIA3_34" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta35_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_35" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me permiten asistir a capacitaciones relacionadas con mi trabajo

                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta35_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta35_3siempre" name="GUIA3_35" value="0">
                            </div>
                            <div>
                                <label for="preguta35_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta35_3casi" name="GUIA3_35" value="1">
                            </div>
                            <div>
                                <label for="preguta35_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta35_3algunas" name="GUIA3_35" value="2">
                            </div>
                            <div>
                                <label for="preguta35_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta35_3casinunca" name="GUIA3_35" value="3">
                            </div>
                            <div>
                                <label for="preguta35_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta35_3nunca" name="GUIA3_35" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta36_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_36" aria-hidden="true" data-toggle="tooltip" title=""></i>

                            Recibo capacitación útil para hacer mi <br>
                            trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta36_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta36_3siempre" name="GUIA3_36" value="0">
                            </div>
                            <div>
                                <label for="preguta36_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta36_3casi" name="GUIA3_36" value="1">
                            </div>
                            <div>
                                <label for="preguta36_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta36_3algunas" name="GUIA3_36" value="2">
                            </div>
                            <div>
                                <label for="preguta36_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta36_3casinunca" name="GUIA3_36" value="3">
                            </div>
                            <div>
                                <label for="preguta36_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta36_3nunca" name="GUIA3_36" value="4">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con el o los jefes con quien tiene contacto.
                        </h6>
                    </div>
                    <div id="pregunta37_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_37" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi jefe me ayuda organizar mejor el trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta37_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta37_3siempre" name="GUIA3_37" value="0">
                            </div>
                            <div>
                                <label for="preguta37_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta37_3casi" name="GUIA3_37" value="1">
                            </div>
                            <div>
                                <label for="preguta37_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta37_3algunas" name="GUIA3_37" value="2">
                            </div>
                            <div>
                                <label for="preguta37_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta37_3casinunca" name="GUIA3_37" value="3">
                            </div>
                            <div>
                                <label for="preguta37_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta37_3nunca" name="GUIA3_37" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta38_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_38" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi jefe tiene en cuenta mis puntos de vista y opiniones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta38_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta38_3siempre" name="GUIA3_38" value="0">
                            </div>
                            <div>
                                <label for="preguta38_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta38_3casi" name="GUIA3_38" value="1">
                            </div>
                            <div>
                                <label for="preguta38_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta38_3algunas" name="GUIA3_38" value="2">
                            </div>
                            <div>
                                <label for="preguta38_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta38_3casinunca" name="GUIA3_38" value="3">
                            </div>
                            <div>
                                <label for="preguta38_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta38_3nunca" name="GUIA3_38" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta39_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_39" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi jefe me comunica a tiempo la información relacionada con el trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta39_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta39_3siempre" name="GUIA3_39" value="0">
                            </div>
                            <div>
                                <label for="preguta39_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta39_3casi" name="GUIA3_39" value="1">
                            </div>
                            <div>
                                <label for="preguta39_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta39_3algunas" name="GUIA3_39" value="2">
                            </div>
                            <div>
                                <label for="preguta39_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta39_3casinunca" name="GUIA3_39" value="3">
                            </div>
                            <div>
                                <label for="preguta39_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta39_3nunca" name="GUIA3_39" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta40_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_40" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            La orientación que me da mi jefe me ayuda a realizar mejor mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta40_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta40_3siempre" name="GUIA3_40" value="0">
                            </div>
                            <div>
                                <label for="preguta40_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta40_3casi" name="GUIA3_40" value="1">
                            </div>
                            <div>
                                <label for="preguta40_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta40_3algunas" name="GUIA3_40" value="2">
                            </div>
                            <div>
                                <label for="preguta40_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta40_3casinunca" name="GUIA3_40" value="3">
                            </div>
                            <div>
                                <label for="preguta40_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta40_3nunca" name="GUIA3_40" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta41_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_41" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mi jefe ayuda a solucionar los problemas <br> que se presentan en el trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta41_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta41_3siempre" name="GUIA3_41" value="0">
                            </div>
                            <div>
                                <label for="preguta41_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta41_3casi" name="GUIA3_41" value="1">
                            </div>
                            <div>
                                <label for="preguta41_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta41_3algunas" name="GUIA3_41" value="2">
                            </div>
                            <div>
                                <label for="preguta41_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta41_3casinunca" name="GUIA3_41" value="3">
                            </div>
                            <div>
                                <label for="preguta41_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta41_3nunca" name="GUIA3_41" value="4">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las preguntas siguientes se refieren a las relaciones con sus compañeros.
                        </h6>
                    </div>

                    <div id="pregunta42_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_42" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Puedo confiar en mis compañeros de <br> trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta42_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta42_3siempre" name="GUIA3_42" value="0">
                            </div>
                            <div>
                                <label for="preguta42_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta42_3casi" name="GUIA3_42" value="1">
                            </div>
                            <div>
                                <label for="preguta42_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta42_3algunas" name="GUIA3_42" value="2">
                            </div>
                            <div>
                                <label for="preguta42_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta42_3casinunca" name="GUIA3_42" value="3">
                            </div>
                            <div>
                                <label for="preguta42_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta42_3nunca" name="GUIA3_42" value="4">
                            </div>
                        </div>
                    </div>


                    <div id="pregunta43_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_43" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Entre compañeros solucionamos los <br> problemas de trabajo de forma respetuosa
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta43_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta43_3siempre" name="GUIA3_43" value="0">
                            </div>
                            <div>
                                <label for="preguta43_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta43_3casi" name="GUIA3_43" value="1">
                            </div>
                            <div>
                                <label for="preguta43_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta43_3algunas" name="GUIA3_43" value="2">
                            </div>
                            <div>
                                <label for="preguta43_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta43_3casinunca" name="GUIA3_43" value="3">
                            </div>
                            <div>
                                <label for="preguta43_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta43_3nunca" name="GUIA3_43" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta44_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_44" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo me hacen sentir parte del<br>
                            grupo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta44_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta44_3siempre" name="GUIA3_44" value="0">
                            </div>
                            <div>
                                <label for="preguta44_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta44_3casi" name="GUIA3_44" value="1">
                            </div>
                            <div>
                                <label for="preguta44_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta44_3algunas" name="GUIA3_44" value="2">
                            </div>
                            <div>
                                <label for="preguta44_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta44_3casinunca" name="GUIA3_44" value="3">
                            </div>
                            <div>
                                <label for="preguta44_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta44_3nunca" name="GUIA3_44" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta45_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_45" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Cuando tenemos que realizar trabajo de <br> equipo los compañeros colaboran
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta45_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta45_3siempre" name="GUIA3_45" value="0">
                            </div>
                            <div>
                                <label for="preguta45_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta45_3casi" name="GUIA3_45" value="1">
                            </div>
                            <div>
                                <label for="preguta45_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta45_3algunas" name="GUIA3_45" value="2">
                            </div>
                            <div>
                                <label for="preguta45_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta45_3casinunca" name="GUIA3_45" value="3">
                            </div>
                            <div>
                                <label for="preguta45_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta45_3nunca" name="GUIA3_45" value="4">
                            </div>
                        </div>
                    </div>


                    <div id="pregunta46_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_46" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Mis compañeros de trabajo me ayudan <br> cuando tengo dificultades
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta46_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta46_3siempre" name="GUIA3_46" value="0">
                            </div>
                            <div>
                                <label for="preguta46_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta46_3casi" name="GUIA3_46" value="1">
                            </div>
                            <div>
                                <label for="preguta46_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta46_3algunas" name="GUIA3_46" value="2">
                            </div>
                            <div>
                                <label for="preguta46_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta46_3casinunca" name="GUIA3_46" value="3">
                            </div>
                            <div>
                                <label for="preguta46_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta46_3nunca" name="GUIA3_46" value="4">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las preguntas siguientes están relacionadas con la información que recibe sobre su rendimiento en el trabajo, el reconocimiento, el sentido
                            de pertenencia y la estabilidad que el ofrece su trabajo.
                        </h6>
                    </div>
                    <div id="pregunta47_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_47" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me informan sobre lo que hago bien en mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta47_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta47_3siempre" name="GUIA3_47" value="0">
                            </div>
                            <div>
                                <label for="preguta47_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta47_3casi" name="GUIA3_47" value="1">
                            </div>
                            <div>
                                <label for="preguta47_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta47_3algunas" name="GUIA3_47" value="2">
                            </div>
                            <div>
                                <label for="preguta47_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta47_3casinunca" name="GUIA3_47" value="3">
                            </div>
                            <div>
                                <label for="preguta47_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta47_3nunca" name="GUIA3_47" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta48_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_48" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            La forma como evalúan mi trabajo en mi <br>centro de trabajo me ayuda a mejorar mi desempeño
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta48_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta48_3siempre" name="GUIA3_48" value="0">
                            </div>
                            <div>
                                <label for="preguta48_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta48_3casi" name="GUIA3_48" value="1">
                            </div>
                            <div>
                                <label for="preguta48_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta48_3algunas" name="GUIA3_48" value="2">
                            </div>
                            <div>
                                <label for="preguta48_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta48_3casinunca" name="GUIA3_48" value="3">
                            </div>
                            <div>
                                <label for="preguta48_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta48_3nunca" name="GUIA3_48" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta49_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_49" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi centro de trabajo me pagan a tiempo <br> mi salario
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta49_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta49_3siempre" name="GUIA3_49" value="0">
                            </div>
                            <div>
                                <label for="preguta49_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta49_3casi" name="GUIA3_49" value="1">
                            </div>
                            <div>
                                <label for="preguta49_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta49_3algunas" name="GUIA3_49" value="2">
                            </div>
                            <div>
                                <label for="preguta49_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta49_3casinunca" name="GUIA3_49" value="3">
                            </div>
                            <div>
                                <label for="preguta49_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta49_3nunca" name="GUIA3_49" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta50_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_50" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            El pago que recibo es el que merezco por el trabajo que realizo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta50_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta50_3siempre" name="GUIA3_50" value="0">
                            </div>
                            <div>
                                <label for="preguta50_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta50_3casi" name="GUIA3_50" value="1">
                            </div>
                            <div>
                                <label for="preguta50_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta50_3algunas" name="GUIA3_50" value="2">
                            </div>
                            <div>
                                <label for="preguta50_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta50_3casinunca" name="GUIA3_50" value="3">
                            </div>
                            <div>
                                <label for="preguta50_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta50_3nunca" name="GUIA3_50" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta51_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_51" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Si obtengo los resultados esperados en mi trabajo me recompensan o reconocen
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta51_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta51_3siempre" name="GUIA3_51" value="0">
                            </div>
                            <div>
                                <label for="preguta51_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta51_3casi" name="GUIA3_51" value="1">
                            </div>
                            <div>
                                <label for="preguta51_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta51_3algunas" name="GUIA3_51" value="2">
                            </div>
                            <div>
                                <label for="preguta51_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta51_3casinunca" name="GUIA3_51" value="3">
                            </div>
                            <div>
                                <label for="preguta51_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta51_3nunca" name="GUIA3_51" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta52_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_52" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Las personas que hacen bien el trabajo <br> pueden crecer laboralmente
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta52_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta52_3siempre" name="GUIA3_52" value="0">
                            </div>
                            <div>
                                <label for="preguta52_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta52_3casi" name="GUIA3_52" value="1">
                            </div>
                            <div>
                                <label for="preguta52_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta52_3algunas" name="GUIA3_52" value="2">
                            </div>
                            <div>
                                <label for="preguta52_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta52_3casinunca" name="GUIA3_52" value="3">
                            </div>
                            <div>
                                <label for="preguta52_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta52_3nunca" name="GUIA3_52" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta53_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_53" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Considero que mi trabajo es estable
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta53_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta53_3siempre" name="GUIA3_53" value="0">
                            </div>
                            <div>
                                <label for="preguta53_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta53_3casi" name="GUIA3_53" value="1">
                            </div>
                            <div>
                                <label for="preguta53_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta53_3algunas" name="GUIA3_53" value="2">
                            </div>
                            <div>
                                <label for="preguta53_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta53_3casinunca" name="GUIA3_53" value="3">
                            </div>
                            <div>
                                <label for="preguta53_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta53_3nunca" name="GUIA3_53" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta54_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_54" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo existe continua rotación de personal
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta54_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta54_3siempre" name="GUIA3_54" value="4">
                            </div>
                            <div>
                                <label for="preguta54_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta54_3casi" name="GUIA3_54" value="3">
                            </div>
                            <div>
                                <label for="preguta54_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta54_3algunas" name="GUIA3_54" value="2">
                            </div>
                            <div>
                                <label for="preguta54_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta54_3casinunca" name="GUIA3_54" value="1">
                            </div>
                            <div>
                                <label for="preguta54_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta54_3nunca" name="GUIA3_54" value="0">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta55_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_55" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Siento orgullo de laborar en este centro de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta55_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta55_3siempre" name="GUIA3_55" value="0">
                            </div>
                            <div>
                                <label for="preguta55_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta55_3casi" name="GUIA3_55" value="1">
                            </div>
                            <div>
                                <label for="preguta55_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta55_3algunas" name="GUIA3_55" value="2">
                            </div>
                            <div>
                                <label for="preguta55_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta55_3casinunca" name="GUIA3_55" value="3">
                            </div>
                            <div>
                                <label for="preguta55_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta55_3nunca" name="GUIA3_55" value="4">
                            </div>
                        </div>
                    </div>

                    <div id="pregunta56_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_56" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me siento comprometido con mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta56_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta56_3siempre" name="GUIA3_56" value="0">
                            </div>
                            <div>
                                <label for="preguta56_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta56_3casi" name="GUIA3_56" value="1">
                            </div>
                            <div>
                                <label for="preguta56_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta56_3algunas" name="GUIA3_56" value="2">
                            </div>
                            <div>
                                <label for="preguta56_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta56_3casinunca" name="GUIA3_56" value="3">
                            </div>
                            <div>
                                <label for="preguta56_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta56_3nunca" name="GUIA3_56" value="4">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6>Las personas siguientes están relacionados con actos de violencia laboral (malos tratos, acoso,
                            hostigamiento, acoso psicológico).
                        </h6>
                    </div>

                    <div id="pregunta57_3" class="mt-5" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_57" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            En mi trabajo puedo expresarme libremente sin interrupciones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta57_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta57_3siempre" name="GUIA3_57" value="0">
                            </div>
                            <div>
                                <label for="preguta57_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta57_3casi" name="GUIA3_57" value="1">
                            </div>
                            <div>
                                <label for="preguta57_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta57_3algunas" name="GUIA3_57" value="2">
                            </div>
                            <div>
                                <label for="preguta57_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta57_3casinunca" name="GUIA3_57" value="3">
                            </div>
                            <div>
                                <label for="preguta57_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta57_3nunca" name="GUIA3_57" value="4">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta58_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_58" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Recibo críticas constantes a mi persona y/o trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta58_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta58_3siempre" name="GUIA3_58" value="4">
                            </div>
                            <div>
                                <label for="preguta58_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta58_3casi" name="GUIA3_58" value="3">
                            </div>
                            <div>
                                <label for="preguta58_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta58_3algunas" name="GUIA3_58" value="2">
                            </div>
                            <div>
                                <label for="preguta58_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta58_3casinunca" name="GUIA3_58" value="1">
                            </div>
                            <div>
                                <label for="preguta58_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta58_3nunca" name="GUIA3_58" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta59_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_59" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Recibo burlas, calumnias, difamaciones, humillaciones o ridiculizaciones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta59_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta59_3siempre" name="GUIA3_59" value="4">
                            </div>
                            <div>
                                <label for="preguta59_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta59_3casi" name="GUIA3_59" value="3">
                            </div>
                            <div>
                                <label for="preguta59_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta59_3algunas" name="GUIA3_59" value="2">
                            </div>
                            <div>
                                <label for="preguta59_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta59_3casinunca" name="GUIA3_59" value="1">
                            </div>
                            <div>
                                <label for="preguta59_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta59_3nunca" name="GUIA3_59" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta60_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_60" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Se ignora mi presencia o se me excluye de <br> las reuniones de trabajo y en la toma de decisiones
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta60_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta60_3siempre" name="GUIA3_60" value="4">
                            </div>
                            <div>
                                <label for="preguta60_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta60_3casi" name="GUIA3_60" value="3">
                            </div>
                            <div>
                                <label for="preguta60_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta60_3algunas" name="GUIA3_60" value="2">
                            </div>
                            <div>
                                <label for="preguta60_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta60_3casinunca" name="GUIA3_60" value="1">
                            </div>
                            <div>
                                <label for="preguta60_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta60_3nunca" name="GUIA3_60" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta61_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_61" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Se manipulan las situaciones de trabajo para hacerme parecer un mal trabajador
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta61_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta61_3siempre" name="GUIA3_61" value="4">
                            </div>
                            <div>
                                <label for="preguta61_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta61_3casi" name="GUIA3_61" value="3">
                            </div>
                            <div>
                                <label for="preguta61_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta61_3algunas" name="GUIA3_61" value="2">
                            </div>
                            <div>
                                <label for="preguta61_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta61_3casinunca" name="GUIA3_61" value="1">
                            </div>
                            <div>
                                <label for="preguta61_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta61_3nunca" name="GUIA3_61" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta62_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_62" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Se ignoran mis éxitos laborales y se atribuyen <br> a otros trabajadores
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta62_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta62_3siempre" name="GUIA3_62" value="4">
                            </div>
                            <div>
                                <label for="preguta62_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta62_3casi" name="GUIA3_62" value="3">
                            </div>
                            <div>
                                <label for="preguta62_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta62_3algunas" name="GUIA3_62" value="2">
                            </div>
                            <div>
                                <label for="preguta62_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta62_3casinunca" name="GUIA3_62" value="1">
                            </div>
                            <div>
                                <label for="preguta62_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta62_3nunca" name="GUIA3_62" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta63_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_63" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            Me bloquean o impiden las oportunidades <br> que tengo para obtener ascenso o mejora en <br> mi trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta63_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta63_3siempre" name="GUIA3_63" value="4">
                            </div>
                            <div>
                                <label for="preguta63_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta63_3casi" name="GUIA3_63" value="3">
                            </div>
                            <div>
                                <label for="preguta63_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta63_3algunas" name="GUIA3_63" value="2">
                            </div>
                            <div>
                                <label for="preguta63_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta63_3casinunca" name="GUIA3_63" value="1">
                            </div>
                            <div>
                                <label for="preguta63_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta63_3nunca" name="GUIA3_63" value="0">
                            </div>
                        </div>
                    </div>
                    <div id="pregunta64_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                        <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_64" aria-hidden="true" data-toggle="tooltip" title=""></i>
                            He presenciado actos de violencia en mi <br> centro de trabajo
                        </p>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div>
                                <label for="preguta64_3siempre" style="margin-right: 5px;">Siempre</label>
                                <input type="radio" id="preguta64_3siempre" name="GUIA3_64" value="4">
                            </div>
                            <div>
                                <label for="preguta64_3casi" style="margin-right: 5px;">Casí siempre</label>
                                <input type="radio" id="preguta64_3casi" name="GUIA3_64" value="3">
                            </div>
                            <div>
                                <label for="preguta64_3algunas" style="margin-right: 5px;">Algunas veces</label>
                                <input type="radio" id="preguta64_3algunas" name="GUIA3_64" value="2">
                            </div>
                            <div>
                                <label for="preguta64_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                                <input type="radio" id="preguta64_3casinunca" name="GUIA3_64" value="1">
                            </div>
                            <div>
                                <label for="preguta64_3nunca" style="margin-right: 5px;">Nunca</label>
                                <input type="radio" id="preguta64_3nunca" name="GUIA3_64" value="0">
                            </div>
                        </div>
                    </div>

                    
                <div class="mt-5">
                    <h6>Las preguntas siguientes están relacionadas con la atención a clientes y usuarios.
                    </h6>
                </div> 	
    
                <div id="preguntaadi1_3" class="mt-5" style="display: flex; align-items: center; margin-bottom: 10px;">
                    <p style="margin: 0; white-space: nowrap; margin-right: 10px;">
                        En mi trabajo debo brindar servicio a clientes o usuarios:
                    </p>
                    <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                        <div style="display: inline-block;">
                            <label for="preguntaadi1_3si" style="line-height: 1; margin-right: 5px;">Sí</label>
                            <input type="radio" id="preguntaadi1_3si" name="GUIAE3_1" value="1" style="vertical-align: middle;" required onchange="clientesyusuariosguia3()">
                        </div>
                        <div style="display: inline-block;">
                            <label for="preguntaadi1_3no" style="line-height: 1; margin-right: 5px;">No</label>
                            <input type="radio" id="preguntaadi1_3no" name="GUIAE3_1" value="0" style="vertical-align: middle;" onchange="clientesyusuariosguia3()">
                        </div>
                    </div>
                </div> 
                <div class="mt-3">
                    <h6>Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO" pase a las preguntas de la sección siguiente.
                    </h6>
                </div> 
            </div>

            <div id="seccion2_3" class="mt-2" style="display: none; padding: 10px;">
                <div id="pregunta65_3" class="mt-3" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_65" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Atiendo clientes o usuarios muy enojados
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta65_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta65_3siempre" name="GUIA3_65" value="4">
                        </div>
                        <div>
                            <label for="preguta65_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta65_3casi" name="GUIA3_65" value="3">
                        </div>
                        <div>
                            <label for="preguta65_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta65_3algunas" name="GUIA3_65" value="2">
                        </div>
                        <div>
                            <label for="preguta65_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta65_3casinunca" name="GUIA3_65" value="1">
                        </div>
                        <div>
                            <label for="preguta65_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta65_3nunca" name="GUIA3_65" value="0">
                        </div>
                    </div>
                </div>
                <div id="pregunta66_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_66" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Mi trabajo me exige atender personas muy necesitadas de ayuda o enfermas
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta66_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta66_3siempre" name="GUIA3_66" value="4">
                        </div>
                        <div>
                            <label for="preguta66_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta66_3casi" name="GUIA3_66" value="3">
                        </div>
                        <div>
                            <label for="preguta66_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta66_3algunas" name="GUIA3_66" value="2">
                        </div>
                        <div>
                            <label for="preguta66_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta66_3casinunca" name="GUIA3_66" value="1">
                        </div>
                        <div>
                            <label for="preguta66_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta66_3nunca" name="GUIA3_66" value="0">
                        </div>
                    </div>
                </div>
                <div id="pregunta67_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_67" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Para hacer mi trabajo debo demostrar sentimientos distintos a los míos
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta67_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta67_3siempre" name="GUIA3_67" value="4">
                        </div>
                        <div>
                            <label for="preguta67_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta67_3casi" name="GUIA3_67" value="3">
                        </div>
                        <div>
                            <label for="preguta67_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta67_3algunas" name="GUIA3_67" value="2">
                        </div>
                        <div>
                            <label for="preguta67_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta67_3casinunca" name="GUIA3_67" value="1">
                        </div>
                        <div>
                            <label for="preguta67_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta67_3nunca" name="GUIA3_67" value="0">
                        </div>
                    </div>
                </div>
                <div id="pregunta68_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_68" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Mi trabajo me exige atender situaciones de violencia
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta68_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta68_3siempre" name="GUIA3_68" value="4">
                        </div>
                        <div>
                            <label for="preguta68_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta68_3casi" name="GUIA3_68" value="3">
                        </div>
                        <div>
                            <label for="preguta68_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta68_3algunas" name="GUIA3_68" value="2">
                        </div>
                        <div>
                            <label for="preguta68_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta68_3casinunca" name="GUIA3_68" value="1">
                        </div>
                        <div>
                            <label for="preguta68_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta68_3nunca" name="GUIA3_68" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <div id="seccion3_3" class="mt-2" style="display: block; padding: 10px;">
                <div id="preguntaadi2_3" class="mt-2" style="display: flex; align-items: center; margin-bottom: 10px;">
                    <p style="margin: 0; white-space: nowrap; margin-right: 10px;">
                        Soy jefe de otros trabajadores
                    </p>
                    <div style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                        <div style="display: inline-block;">
                            <label for="preguntaadi2_3si" style="line-height: 1; margin-right: 5px;">Sí</label>
                            <input type="radio" id="preguntaadi2_3si" name="GUIAE3_2" value="1" style="vertical-align: middle;" required onchange="jefetrabajadoresguia3()">
                        </div>
                        <div style="display: inline-block;">
                            <label for="preguntaadi2_3no" style="line-height: 1; margin-right: 5px;">No</label>
                            <input type="radio" id="preguntaadi2_3no" name="GUIAE3_2" value="0" style="vertical-align: middle;" onchange="jefetrabajadoresguia3()">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Si su respuesta fue "SI", responda las preguntas siguientes. Si su respuesta fue "NO", ha concluido el cuestionario.
                    </h6>
                </div>
            </div>


            <div id="seccion4_3" class="mt-2" style="display: none; padding: 10px;">
                <div class="mt-3">
                    <h6>Las siguientes preguntas están relacionadas con las actitudes de los trabajadores que supervisa.
                    </h6>
                </div>
                <div id="pregunta69_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_69" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Comunican tarde los asuntos del trabajo
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta69_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta69_3siempre" name="GUIA3_69" value="4">
                        </div>
                        <div>
                            <label for="preguta69_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta69_3casi" name="GUIA3_69" value="3">
                        </div>
                        <div>
                            <label for="preguta69_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta69_3algunas" name="GUIA3_69" value="2">
                        </div>
                        <div>
                            <label for="preguta69_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta69_3casinunca" name="GUIA3_69" value="1">
                        </div>
                        <div>
                            <label for="preguta69_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta69_3nunca" name="GUIA3_69" value="0">
                        </div>
                    </div>
                </div>
                <div id="pregunta70_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_70" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Dificultan el logro de los resultados del <br> trabajo
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta70_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta70_3siempre" name="GUIA3_70" value="4">
                        </div>
                        <div>
                            <label for="preguta70_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta70_3casi" name="GUIA3_70" value="3">
                        </div>
                        <div>
                            <label for="preguta70_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta70_3algunas" name="GUIA3_70" value="2">
                        </div>
                        <div>
                            <label for="preguta70_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta70_3casinunca" name="GUIA3_70" value="1">
                        </div>
                        <div>
                            <label for="preguta70_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta70_3nunca" name="GUIA3_70" value="0">
                        </div>
                    </div>
                </div>

                <div id="pregunta71_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_71" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Cooperan poco cuando se necesita
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta71_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta71_3siempre" name="GUIA3_71" value="4">
                        </div>
                        <div>
                            <label for="preguta71_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta71_3casi" name="GUIA3_71" value="3">
                        </div>
                        <div>
                            <label for="preguta71_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta71_3algunas" name="GUIA3_71" value="2">
                        </div>
                        <div>
                            <label for="preguta71_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta71_3casinunca" name="GUIA3_71" value="1">
                        </div>
                        <div>
                            <label for="preguta71_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta71_3nunca" name="GUIA3_71" value="0">
                        </div>
                    </div>
                </div>

                <div id="pregunta72_3" class="mt-4" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                    <p style="margin: 0; flex: 1;"><i class="fa fa-question-circle" id="Exp3_72" aria-hidden="true" data-toggle="tooltip" title=""></i>
                        Ignoran las sugerencias para mejorar <br> su trabajo
                    </p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div>
                            <label for="preguta72_3siempre" style="margin-right: 5px;">Siempre</label>
                            <input type="radio" id="preguta72_3siempre" name="GUIA3_72" value="4">
                        </div>
                        <div>
                            <label for="preguta72_3casi" style="margin-right: 5px;">Casí siempre</label>
                            <input type="radio" id="preguta72_3casi" name="GUIA3_72" value="3">
                        </div>
                        <div>
                            <label for="preguta72_3algunas" style="margin-right: 5px;">Algunas veces</label>
                            <input type="radio" id="preguta72_3algunas" name="GUIA3_72" value="2">
                        </div>
                        <div>
                            <label for="preguta72_3casinunca" style="margin-right: 5px;">Casi nunca</label>
                            <input type="radio" id="preguta72_3casinunca" name="GUIA3_72" value="1">
                        </div>
                        <div>
                            <label for="preguta72_3nunca" style="margin-right: 5px;">Nunca</label>
                            <input type="radio" id="preguta72_3nunca" name="GUIA3_72" value="0">
                        </div>
                    </div>
                </div>               
            </div>
            <button type="submit" class="btn btn-danger" id="guardar_guia3">
                Guardar <i class="fa fa-save"></i>
            </button>
            </form>
        </div>


</div>




    <script type="text/javascript">
        var requiereGuia1 = <?php echo json_encode($guia1); ?>;
        var requiereGuia2 = <?php echo json_encode($guia2); ?>;
        var requiereGuia3 = <?php echo json_encode($guia3); ?>;
        var id = <?php echo json_encode($id); ?>;

        document.addEventListener('DOMContentLoaded', function() {
        mostrarGuias(requiereGuia1, requiereGuia2, requiereGuia3);
    });
    </script>


    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="/assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>
  
    <script src="/js_sitio/guias.js"></script>



</body>

</html>