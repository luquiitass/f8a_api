<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

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
    <h2>
        {{$data['text']}}
    </h2>
    <br>

    <a class="myButton" href="{{$data['goTo']}}" target="_blank" rel="noopener noreferrer">Ver Partido</a>
</body>
</html>