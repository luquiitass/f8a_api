<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Game;
use App\Models\Team;

class FunctionsDeployController extends Controller
{
    //


    public function setWinner(){
        $games = Game::get();

        foreach($games as $game){
            $game->setWinner();
        }

        echo json_encode($games);

        
    }
}
