<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbol 8</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"  crossorigin="anonymous">

</head>
<body>
    <h1>¿Ya te encuentras en el grupo de WhatsApp de <b>Fútbol 8 Alem</b>?</h1>
    <br>
    @if(!empty($data->teams))
        <h3>Eres administrador de:</h3>
        <ul class="list-group">
        @foreach($data->teams as $team)
            <li class="list-group-item">
                <a href="https://futbol8alem.com/#/team/profile/{{$team->id}}">{{$team->name}}</li>
        @endforeach
        </ul>
    @endif
    <div class="alert alert-info">
        El grupo es utilizado para realizar consultas sobre las diferentes funciones de la pagina a el administrador, pasar resultados, partidos, etc. 
    <div>

    <h3>Ingresa al siguiente link para formar parte del grupo </h3>

    <a href="https://chat.whatsapp.com/01bWFvD2lARL2XXI7oUm0g" > Ingresar al grupo</a>
    
</body>
</html> 