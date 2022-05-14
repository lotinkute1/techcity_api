<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Question;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BotmanController extends Controller
{
    public function handle()
    {
        $botman = app('botman');
        $botman->hears('{message}', function ($botman, $message) {

            if ($message == 'tôi đã mua những gì' || $message == 'những gì tôi đã mua' || $message == 'cho tôi xem lịch sử mua hàng' || $message == 'lịch sử mua hàng' || $message == 'lịch sử mua hàng của tôi' || $message == 'tui đã mua những gì' || $message == 'những gì tui đã mua' || $message == 'cho tui xem lịch sử mua hàng' || $message == 'lịch sử mua hàng của tui') {
                $botman->reply('<a target="_blank" rel="noopener noreferrer" href="http://localhost:3000/my-account/purchase-history">Click vào đây để đến lịch sử mua hàng</a>');
            } else if ($message == 'cảm ơn' || $message == 'cảm ơn nha' || $message == 'tam giác') {
                $botman->reply("Không có gì");
            } else if ($message == 'bên mình thanh toán bằng hình thức gì' || $message == 'hình thức thanh toán bên mình là gì' || $message == 'hình thức thanh toán ở đây' || $message == 'hình thức thanh toán ở đây là gì' ||  $message == 'hình thức thanh toán') {
                $botman->reply("Bên mình có 2 hình thức thanh toán: Thanh toán trực tiếp & Thanh toán paypal");
            } else {
                $this->askProduct($botman);
            }
        });
        $botman->listen();
    }

    public function askProduct($botman)
    {
        $botman->ask("Bạn cần tìm gì?", function (Answer $answer) {
            $name = $answer->getText();
            // $question=Question::where('question','like','%'.$name.'%')->get()->first();
            // $this->aks("Có phải bạn muốn tìm ".$question->answer,function(Answer $answer,$question){
            //      if($answer->getText()=="yes"||$answer->getText()=="Yes"||$answer->getText()=="y"||$answer->getText()=="đúng"||$answer->getText()=="Đúng"||$answer->getText()=="dung"){
            //         $reponse=Product::where('name',"like",'%'.$question->answer.'%')->get()->first();
            //         $this->say('<img src="'.$reponse->img.'" alt="'.$reponse->name.'" width="300"/>');
            //      }
            // });
            $name = str_replace([' nè', 'tui muốn tìm ', 'tôi muốn tìm ', 'tui tìm ', 'tui muốn tìm ', ' á', ' ấy', ' đó', ' nhá', 'tôi muốn ', 'tui muốn '], '', $name);
            $cate = Category::where('category_name', 'like', '%' . $name . '%')->get()->first();
            if ($cate) {
                $this->say('<a target="_blank" rel="noopener noreferrer" href="http://localhost:3000/show-all-product/' . $cate->id . '">Click vào đây để đến ' . $cate->category_name . ' </a>');
            } else {
                $reponse = Product::where('name', "like", '%' . $name . '%')->get();

                if (!$reponse) {
                    $this->say(" Tôi không hiểu gì bạn nói");
                } else {
                    foreach ($reponse as $id => $res) {
                        $this->say(' <a target="_blank" rel="noopener noreferrer" href="http://localhost:3000/product_info-' . $res->id . '" ><div class="card" style="width: 18rem;">
                       <img class="card-img-top" width="250" src="' . $res->img . '" alt="Card image cap">
                        <div class="card-body">
                          <h5 class="card-title">' . $res->name . '</h5>
                        </div>
                      </div></a>');
                    }
                    // $this->say('<img src="' . $reponse->img . '" alt="' . $reponse->name . '" width="300"/>');
                    // $this->say('<a target="_blank" rel="noopener noreferrer" href="http://localhost:3000/show-all-product?filterType=name&filterVal=' . $reponse->name . '">Click Me</a>');
                }
            }
        });
    }
}
