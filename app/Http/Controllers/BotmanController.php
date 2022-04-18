<?php

namespace App\Http\Controllers;

use App\Models\Product;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Http\Request;

class BotmanController extends Controller
{
    public function handle(){
        $botman = app('botman');
        $botman->hears('{message}', function($botman, $message){

           if($message=='hi'){
               $this->askName($botman);
           }
           else{
               $this->askProduct($botman);
           }
        });
        $botman->listen();
    }
    public function askName($botman){
        $botman->ask("hello! what your name?", function(Answer $answer){
            $name = $answer->getText();
            $this->say("hello ".$name);
        });
    }
    public function askProduct($botman){
        $botman->ask("your find product name?", function(Answer $answer){
            $name = $answer->getText();
            $reponse=Product::where('name',"like",'%'.$name.'%')->get()->first();
            $this->say('<img src="'.$reponse->img.'" alt="'.$reponse->name.'" width="300"/>');
        });
    }
}
