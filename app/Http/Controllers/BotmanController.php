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

           if($message=='xin chào'){
               $this->askName($botman);
           }
           else{
               $this->askProduct($botman);
           }
        });
        $botman->listen();
    }
    public function askName($botman){
        $botman->ask("xin chào tên bạn là gì?", function(Answer $answer){
            $name = $answer->getText();
            $this->say("xin chào ".$name);
        });
    }
    public function askProduct($botman){
        $botman->ask("Sản phẩm bạn cần tìm tên gì?", function(Answer $answer){
            $name = $answer->getText();
            $reponse=Product::where('name',"like",'%'.$name.'%')->get()->first();
            $this->say('<img src="'.$reponse->img.'" alt="'.$reponse->name.'" width="300"/>');
        });
    }
}
