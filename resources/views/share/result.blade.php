<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('assets/favicon.png')}}" type="image/x-icon" />

    
    

    <style>
        body{
            width: 500px;
            height: 300px;
        }
        .content{
            color: white;
            display: inline-flex;
            width: 500px;
            height: 300px;
            /*background-image: url("<?php echo url('assets/bg.jpg') ?>");*/
            background-color: black;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .c-team{
            display: flex;
            align-items: center;
        }

        .team{
            font-size: 30px;
        }

        .logo{
            width: 100px;
            margin: 10px;
        }

        .f8a{
            width: 30%;
            align-items: center;
            display: flex;
        }

        .data{
            display: flow-root;
            width: 70%;
            align-self: center;
        }

        .avatar{
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .goal{
            margin-left: 20px;
            font-weight: 200;
        }

        .vs{
            margin-left: 20%;
        }

        .v{
            margin-left: 40%;
        }

        .date{
            text-align: center;;
        }

    </style>
    
</head>
<body>
    <div id="content">
        <div class="content" >
        <div class="f8a">
            <img class="logo" src="{{url('assets/favicon.png')}}">
        </div>
        <div class="center data">    
            <div class="l">
                <div class="c-team">
                    <img class="avatar" src="{{$game->team_l->shield ? $game->team_l->shield ->urlComplete : url('assets/esc.png')}}">
                    <h2 class="team">{{$game->team_l->name}}</h2>

                    @if($game->status == "Jugado")
                        <h1 class="goal">{{$game->l_goals}}</h1>
                    @endif

                </div>
                
              
            </div>
            <div class="vs">{{$game->status == 'Jugado' ? '-' : 'vs'}}</div>
            <div class="v">
                <div class="c-team">
                    <img class="avatar" src="{{$game->team_v->shield ? $game->team_v->shield ->urlComplete : url('assets/esc.png')}}">
                    <h2 class="team">{{$game->team_v->name}}</h2>
                    @if($game->status == "Jugado")
                    <h1 class="goal">{{$game->v_goals}}</h1>
                    @endif
                </div>
              
            </div> 
            <div class="date">Fecha: {{$game->date}}  a las {{$game->time}}</div>
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
        //return;
        //document.body.appendChild(canvas)
        //let element = document.querySelector("#content").
        //element.parentNode.removeChild(element);
        //document.body.appendChild(canvas)
        //console.log('test')
        var myImage = canvas.toDataURL("image/png");
        //console.log('mi image',myImage)
        //return;
        let url = "<?php echo url('api2/runFunctionModel/Game/'.$game->id.'/addPreview'); ?>"

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
                 if(response['status'] = 'success'){
                     console.log('response ajax', response);
                     window.location.href = url;

                     //let image = response['Image'];
                     //console.log('image',image);
                     //let new_url = "<?php echo  url('api2/model/Image/') ?>/" + image.id;
                     //console.log('new path' , new_url)
                 }
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