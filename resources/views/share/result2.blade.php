<?php
    $col = $game->status == 'Jugado' ? 'col-4' : 'col-5';
    $font_size =  $game->status == 'Jugado' ? 'larger' : 'xx-large';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('assets/favicon.png')}}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    
    

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
            display: flex;
            width: 600px;
            height: 300px;
            /*background-color: black;*/

        }


        .logo{
            width: 300px;
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0.1;
        }

        .text-center-start{
            align-self: flex-start;
            text-align: center;
        }

        .text-center{
            text-align: center;
        }

        .text-bottom{
            text-align: end;
        }

        .avatar{
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .goal{
            font-size: xxx-large;
            font-weight: 700;
            text-align: center;
            margin: auto;
        }

        .team{
            font-weight: 200;
            font-family: -webkit-body;
            font-size: <?php echo $font_size ; ?> ;
            margin-top: 10px;
        }

        .avatar_event{
            width: 25px;
        }

        .event{
            font-size: small;
        }
        .events{
            max-height: 300px;
        }
        .result{
            background: #00000059;
            border-radius: 10px;
        }
        .date{
            margin-top: 20px;
            font-family: fantasy;
            color: #e9eaead4;

        }

        .vs{
            font-weight: 100;
            font-size: xx-large;
            font-family: none;
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


                <div class="row justify-content-centers" style="width: 100%;margin:auto;">
                    
                    <div class="{{$col}} text-center-start">
                        <div>
                            <img class="avatar" src="{{$game->team_l->shield ? $game->team_l->shield ->urlComplete : asset('assets/esc.png')}}">
                        </div>
                        <div class="team">
                        {{$game->team_l->name}}
                        </div>
                    </div>
                    
                    @if($game->status == 'Jugado')
                        <div class="{{$col}}" style="margin: auto;">
                            <div class="row result">
                                <div class="col-4 text-center-start goal">
                                    {{$game->l_goals}}
                                </div>

                                <div class="col-4 text-center-start goal">
                                    -
                                </div>

                                <div class="col-4 text-center-start goal">
                                    {{$game->v_goals}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-2 goal vs">vs</div>
                    @endif
                    
                    <div class="{{$col}} text-center-start">
                        <div>
                            <img class="avatar" src="{{$game->team_v->shield ? $game->team_v->shield ->urlComplete : asset('assets/esc.png')}}">
                        </div>
                        <div class="team">
                            {{$game->team_v->name}}
                        </div>                
                    </div>

                    @if($game->status != 'Jugado')
                        <div class="col-12 text-center date" >
                            {{ date('d/m/Y', strtotime($game->date))  }}  a las {{ \Carbon\Carbon::createFromFormat('H:i:s',$game->time)->format('h:i')  }}
                        </div>
                    @endif
                </div>


                <!-- <div class="row events" >
                    <div class="col-6">
                        <div class="list-group">
                            @foreach($game->events as $e)
                                @if($e->team_id == $game->team_l->id && $e->player && $e->typeEvent->name == 'Gol' )
                                <div class="event" >
                                    <img class="avatar_event"  src="{{asset('assets/' . $e->typeEvent->icon)}}">
                                    {{$e->player->name}} 
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            @foreach($game->events as $e)
                                @if($e->team_id == $game->team_v->id && $e->player && $e->typeEvent->name == 'Gol' )
                                <div class="event">
                                    <img class="avatar_event" src="{{asset('assets/' . $e->typeEvent->icon)}}">
                                    {{$e->player->name}} 
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div> -->

                
            
            </div>
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
        //return;
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