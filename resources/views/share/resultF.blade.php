<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partido: {{$game->team_l->name}} vs {{$game->team_v->name}} </title>
    <meta property="og:image" content="{{ $game->preview->urlComplete }}" />
    <meta property="fb:app_id" content="140247018227426" />
    <meta property="og:url" content="<?php echo 'https://futbol-alem.com/#/results/profile/' . $game->id ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Partido : {{$game->team_l->name}} vs {{$game->team_v->name}}" />
    <meta property="og:description" content="Fecha: {{ date('d/m/Y', strtotime($game->date))  }}  a las {{ \Carbon\Carbon::createFromFormat('H:i:s',$game->time)->format('h:i')  }}" />
    <meta property="og:site_name" content="Fútbol 8 alem" />


    
</head>
<body>
</body>
<script>


window.onload = function() {
    window.location.href = "<?php echo 'https://futbol-alem.com/#/results/profile/' . $game->id ?>"

};
</script>
</html>