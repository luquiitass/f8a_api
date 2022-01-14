<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbol 8</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"  crossorigin="anonymous">

    <style>
        .myButton {
            box-shadow:inset 0px 0px 7px 3px #23395e;
            background:linear-gradient(to bottom, #2e466e 5%, #415989 100%);
            background-color:#2e466e;
            border-radius:17px;
            border:1px solid #1f2f47;
            display:inline-block;
            cursor:pointer;
            color:#ffffff !important;
            font-family:Arial;
            font-size:16px;
            padding:9px 29px;
            text-decoration:none;
            text-shadow:0px 1px 0px #263666;
        }
        .myButton:hover {
            background:linear-gradient(to bottom, #415989 5%, #2e466e 100%);
            background-color:#415989;
        }
        .myButton:active {
            position:relative;
            top:1px;
        }
    </style>

</head>
<body>
    <h1>¿Ya create el partido de esta fecha en <b>Fútbol 8 Alem</b>?</h1>
    <br>
    @if(!empty($data->teams))
        <h3>Eres administrador de los siguientes equipos:</h3>
        <ul class="list-group">
        @foreach($data->teams as $team)
            <li class="list-group-item">
                <a href="https://futbol8alem.com/#/team/profile/{{$team->id}}">{{$team->name}}</li>
        @endforeach
        </ul>
    @endif
    <div class="alert alert-info">
        Puedes crear partidos de las siguientes fechas.
    <div>

    <h3>Ingresa al siguiente link para registrar tu partido </h3>

    <a class="myButton" href="https://futbol8alem.com/#/home/games?create=true" >Crear Partido</a>
    
</body>
</html> 