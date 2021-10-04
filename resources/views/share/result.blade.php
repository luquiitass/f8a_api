<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('assets/favicon.png')}}" type="image/x-icon" />

    
    

    <style>
        body{
            width: 600px;
            height: 300px;
            position: relative;
        }

        .bg{
            background-image: url("<?php echo asset('assets/bg.jpg') ?>");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;

        }

        .content{
            color: #ffffff;
            display: inline-flex;
            width: 600px;
            height: 300px;
            /*background-color: black;*/

        }

        .c-team{
            display: flex;
            align-items: center;
            text-shadow: 5px 5px 5px grey;
        }

        .team{
            color: #ffffff;
            font-size: 30px;
        }

        .logo{
            width: 300px;
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0.1;
        }

        .f8a{
            width: 10%;
            align-items: center;
            display: flex;
        }

        .data{
            display: flow-root;
            width: 90%;
            align-self: center;
            margin-right: 20px;
        }

        .avatar{
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .goal{
            margin-left: 20px;
            font-weight: 900;
            text-shadow: 5px 5px 5px grey;
            padding-right: 10px;
            background: #1f202199;
            border-radius: 20px;
            padding-left: 10px;

        }

        .vs{
            margin-left: 10%;
        }

        .v{
            margin-left: 20%;

        }

        .date{
            text-align: right;;
            text-shadow: 5px 5px 5px grey;
            margin-right: 50px;

        }

        .loader{
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('//upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Phi_fenomeni.gif/50px-Phi_fenomeni.gif') 
                        50% 50% no-repeat rgb(249,249,249);
        }

        #loading {
            position: absolute;
            display: block;
            width: 100%;
            height: 100%;
            top: -1px;
            left: 0;
            text-align: center;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
            padding-top: 4%;
        }

        .se-pre-con {
            display: none;
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("<?php echo asset('assets/preloader.gif'); ?>" ) left top no-repeat #fff;
        }


    </style>

</head>
<body>

    <div id="content">
        <div class="bg">

        <div class="content" >
            <div class="f8a">
                <img class="logo" src="{{asset('/assets/favicon.png')}}">
            </div>

            <div class="center data">    
                <div class="l">
                    <div class="c-team">
                        <img class="avatar" src="{{$game->team_l->shield ? $game->team_l->shield ->urlComplete : asset('assets/esc.png')}}">
                        <h2 class="team">{{$game->team_l->name}}</h2>

                        @if($game->status == "Jugado")
                            <h1 class="goal">{{$game->l_goals}}</h1>
                        @endif

                    </div>
                    
                
                </div>
                <div class="vs">{{$game->status == 'Jugado' ? '' : 'vs'}}</div>
                <div class="v">
                    <div class="c-team">
                        <img class="avatar" src="{{$game->team_v->shield ? $game->team_v->shield ->urlComplete : asset('assets/esc.png')}}">
                        <h2 class="team">{{$game->team_v->name}}</h2>
                        @if($game->status == "Jugado")
                        <h1 class="goal">{{$game->v_goals}}</h1>
                        @endif
                    </div>
                
                </div> 
                <div class="date">Fecha: {{ date('d/m/Y', strtotime($game->date))  }}  a las {{ \Carbon\Carbon::createFromFormat('H:i:s',$game->time)->format('h:i')  }}</div>
            </div>
            
            <!-- <div style="display: none;">
                    <div class="l">
                        <ul class="list_events">
                            @foreach($game->events as $e)
                                @if($e->team_id == $game->team_l->id && $e->typeEvent->name == 'Gol')
                                <li>
                                    <img class="avatar_event" src="{{asset('assets/' . $e->typeEvent->icon)}}">
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
                                    <img class="avatar_event" src="{{asset('assets/' . $e->typeEvent->icon)}}">
                                    {{$e->player->name}} 
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>   
            </div> -->

        </div>
    </div>

    
</body>
<div id="load" class="se-pre-con"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('assets/js/html2canvas.min.js')}}"></script>
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
        return;
        //document.body.appendChild(canvas)
        //let element = document.querySelector("#content").
        //element.parentNode.removeChild(element);
        //document.body.appendChild(canvas)
        //console.log('test')
        var myImage = canvas.toDataURL("image/png");
        //console.log('mi image',myImage)
        //return;

        let url = "<?php echo url('/api2/runFunctionModel/Game/' . $game->id . '/addPreview') ; ?>"

        var formData = new FormData();
        formData.append('data', myImage);
        formData.append('url','/results/');
        formData.append('thumb',false);

        document.getElementById('load').style.display = 'block'

        $.ajax({
            method:'POST',
            url:url,
            data : formData,
            contentType: false,
            processData: false,success: function(response){
                 console.log(response);
                 if(response['status'] = 'success'){
                     console.log('response ajax', response);


                     //local
                     window.location.href = "https://www.facebook.com/sharer?u=<?php echo url('/') ; ?>/shareResultF/<?php echo $game->id; ?>";
                     
                     //prod
                     //window.location.href = `https://www.facebook.com/sharer?u=http://api.futbol8alem.com/shareResultF/${id}`;

                     //let image = response['Image'];
                     //console.log('image',image);
                     //let new_url = "<?php echo  url('api2/model/Image/') ?>/" + image.id;
                     //console.log('new path' , new_url)
                 }
                         document.getElementById('load').style.display = 'block'

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