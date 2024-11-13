<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results In Performance</title>
</head>

<body>
    <h3>Estimado(a): {{ $name }}</h3>
    <p>A continuación encontrará un enlace con los cuestionarios para evaluar los factores de riesgo psicosocial de su centro de trabajo, de acuerdo con lo establecido en la NOM-035-STPS-2018.</p>

    @if ($dias == 0)

    <p>La prueba es individual, confidencial y solo tiene el dia de hoy para realizarla. </p>

    @else

    <p>La prueba es individual, confidencial y a partir de la fecha cuenta con {{ $dias }} días para realizarla. </p>

    @endif

    <p>En caso de cualquier inquietud o información adicional que requiera, por favor no dude en contactarnos al 999 357 8332 ({{ $psico }})</p>
    <p>Cordialmente,</p>
    <p>Coordinación de evaluación del FRP</p>

    <!-- <a href="http://127.0.0.1:8000/Guia/{{ $guia1 }}/{{ $guia2 }}/{{ $guia3 }}/{{ $guia5 }}/{{ $status }}/{{ $fechalimite }}/{{ $id }}">Responder cuestionario aquí</a> -->

    <a href="https://desarrolloti.results-in-performance.com/Guia/{{ $guia1 }}/{{ $guia2 }}/{{ $guia3 }}/{{ $guia5 }}/{{ $status }}/{{ $fechalimite }}/{{ $id }}">Responder cuestionario aquí</a>

</body>

</html>