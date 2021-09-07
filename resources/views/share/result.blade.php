<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    

    <style>
        .f{
            color: white;
            width: 500px;
            height: 300px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-image: url("{{{url('assets/f1.jpg')}}}");
        }

        .avatar {
            border-radius: 50%;
            /*background-image: url('avatar.png');*/
            background-position: center;
            background-size: cover;
            height: 64px;
            width: 64px;
        }
        .avatar_event {
            border-radius: 50%;
            /*background-image: url('avatar.png');*/
            background-position: center;
            background-size: cover;
            height: 15px;
            width: 15px;
        }

        .center {
            display: inline-flex;
            justify-content: end;
            align-items: center;
            /* height: 100%; */
            width: 100%;
            margin-top: 20px;
            }


        .l{
            text-align: center;
            width: 45%;
        }
        .v{
            text-align: center;
            width: 45%;
        }
        .vs{
            text-align: center;
            width: 10%;
        }

        .goal{
            font-size: xx-large;
        }

        .list_events{
            list-style-type: none;
            text-align: start;
        }
    </style>
    
</head>
<body>
    <div class="f" id="content">
        <div class="center">    
            <div class="l">
                <img class="avatar" src="{{$game->team_l->shield ? $game->team_l->shield ->urlComplete : url('assets/esc.png')}}">
                <h2>{{$game->team_l->name}}</h2>
                <h1 class="goal">{{$game->l_goals}}</h1>
            </div>
            <div class="vs">-</div>
            <div class="v">
                <img class="avatar" src="{{$game->team_v->shield ? $game->team_v->shield ->urlComplete : url('assets/esc.png')}}">
                <h2>{{$game->team_v->name}}</h2>
                <h1 class="goal">{{$game->v_goals}}</h1>
            </div> 
        </div>
        <div>
                <div class="l">
                    <ul class="list_events">
                        @foreach($game->events as $e)
                            @if($e->team_id == $game->team_l->id && $e->typeEvent->name == 'Gol')
                            <li>
                                <img class="avatar_event" src="{{url('assets/' . $e->typeEvent->icon)}}">
                                {{$e->time}}´ 
                                {{$e->player->name}} 
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="v">
                    <ul class="list_events">
                        @foreach($game->events as $e)
                            @if($e->team_id == $game->team_v->id  && $e->typeEvent->name == 'Gol' )
                            <li>
                                <img class="avatar_event" src="{{url('assets/' . $e->typeEvent->icon)}}">
                                {{$e->player->name}} 
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

    </div>
    
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="{{url('assets/js/html2canvas.min.js')}}"></script>
<script>

window.onload = function() {
        let capture = document.querySelector("#content");
        printToFile(capture);
   

};


function downloadURI(uri, name) {
    var link = document.createElement("a");
    link.download = name;
    link.href = uri;
    link.click();
    //after creating link you should delete dynamic link
    //clearDynamicLink(link); 
}

//Your modified code.
function printToFile(div) {
    /*html2canvas(div, {
        onrendered: function (canvas) {
            console.log('re rende')
            var myImage = canvas.toDataURL("image/png");
            //create your own dialog with warning before saving file
            //beforeDownloadReadMessage();
            //Then download file
            downloadURI("data:" + myImage, "yourImage.png");
        }
    });
*/
    html2canvas(div).then(canvas => {
        //document.body.appendChild(canvas)
        console.log('test')
        var myImage = canvas.toDataURL("image/png");
        console.log('mi image',myImage)
        var url = "<?php echo url('api2/methods/Image/create?api_token=ZllhL8MT9UvOv1oX3gR2Y9hHfr4lMq723jc6LXewLyaDAQWrq3') ?>" 
     

        var formData = new FormData();
        formData.append('data', myImage);
        formData.append('url','/results/');
        formData.append('thumb',false);

        $.ajax({
            method:'POST',
            url:url,
            data : formData,
            contentType: false,
            processData: false,success: function(response){
                 console.log(response);
              },
           });
            //create your own dialog with warning before saving file
            //beforeDownloadReadMessage();
            //Then download file
            //downloadURI("data:" + myImage, "yourImage.png");
    });

}
</script>
</html>