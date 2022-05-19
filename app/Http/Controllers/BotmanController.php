<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Question;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BotmanController extends Controller
{
    public function handle()
    {
        $botman = app('botman');
        $botman->hears('{message}', function ($botman, $message) {
            function convert_name($str)
            {
                $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
                $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
                $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
                $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
                $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
                $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
                $str = preg_replace("/(đ)/", 'd', $str);
                $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
                $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
                $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
                $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
                $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
                $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
                $str = preg_replace("/(Đ)/", 'D', $str);
                $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\?|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '', $str);
                $str = preg_replace("/( )/", '', $str);
                return $str;
            }
            if (Str::lower(convert_name($message)) == 'toidamuanhunggi' || Str::lower(convert_name($message)) == 'nhunggitoidamua' || Str::lower(convert_name($message)) == 'chotoixemlichsumuahang' || Str::lower(convert_name($message)) == 'lichsumuahang' || Str::lower(convert_name($message)) == 'lichsumuahangcuatoi' || Str::lower(convert_name($message)) == 'tuidamuanhunggi' || Str::lower(convert_name($message)) == 'nhunggituidamua' || Str::lower(convert_name($message)) == 'chotuixemlichsumuahang' || Str::lower(convert_name($message)) == 'lichsumuahangcuatui' || Str::lower(convert_name($message)) == 'toimuonxemlichsumuahang'  ) {
                $botman->reply('<a target="_blank" rel="noopener noreferrer" href="http://localhost:3000/my-account/purchase-history">Click vào đây để đến lịch sử mua hàng</a>');
            } else if (Str::lower(convert_name($message)) == 'camon' || Str::lower(convert_name($message)) == 'camonha' || Str::lower(convert_name($message)) == 'tamgiac') {
                $botman->reply("Không có gì nha ^^");
            } else if (Str::lower(convert_name($message)) == 'benminhthanhtoanbanghinhthucgi' || Str::lower(convert_name($message)) == 'hinhthucthanhtoanbenminhlagi' || Str::lower(convert_name($message)) == 'hinhthucthanhtoanodaylagi' || Str::lower(convert_name($message))  == 'hinhthucthanhtoanodaylasao' ||  Str::lower(convert_name($message))  == 'hinhthucthanhtoan' || Str::lower(convert_name($message)) == 'odaythanhtoanbanghinhthucgi' || Str::lower(convert_name($message)) == 'shopminhthanhtoanbanghinhthucgi' || Str::lower(convert_name($message)) == 'hinhthucthanhtoanbenshopminhlagi' || Str::lower(convert_name($message)) == 'hinhthucthanhtoanbencuahanglagi' || Str::lower(convert_name($message)) == 'benminhthanhtoanbanggi' || Str::lower(convert_name($message)) == 'odaythanhtoanbanggi' || Str::lower(convert_name($message)) == 'shopthanhtoanbanggi' || Str::lower(convert_name($message)) == 'cuahangthanhtoanbanggi' || Str::lower(convert_name($message)) == 'cuahangcuaminhthanhtoanbanggi')  {
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
            $name = str_replace(['tôi cần tìm ', ' nè', 'tui muốn tìm ', 'tôi muốn tìm ', 'tui tìm ', ' á', ' ấy', ' đó', ' nhá', 'tôi muốn ', 'tui muốn ', ' ne', 'tui muon tim ', 'tui tim ', 'toi muon tim ', ' a', ' ay', ' do', ' nha', 'toi muon ', 'tui muon ', 'toi can tim ', 'TÔI CẦN TÌM ', ' NÈ', 'TUI MUỐN TÌM ', 'TÔI MUỐN TÌM ', 'TUI TÌM ', ' Á', ' ẤY', ' ĐÓ', ' NHÁ', 'TÔI MUỐN ', 'TUI MUỐN ', ' NE', 'TUI MUON TIM ', 'TUI TIM ', 'TOI MUON TIM ', ' A', ' AY', ' DO', ' NHA', 'TOI MUON ', 'TUI MUON ', 'TOI CAN TIM ', 'Tôi cần tìm ', ' Nè', 'Tui muốn tìm ', 'Tôi muốn tìm ', 'Tui tìm ', 'Tôi muốn ', 'Tui muốn ', 'Tui muon tim ', 'Tui tim ', 'Toi muon tim ', 'Toi muon ', 'Tui muon ', 'Toi can tim '], '', $name);
            $cate = Category::where('category_name', 'like', '%' . $name . '%')->get()->first();
            if ($cate) {
                $this->say('<a target="_blank" rel="noopener noreferrer" href="http://localhost:3000/show-all-product/' . $cate->id . '">Click vào đây để đến ' . $cate->category_name . ' </a>');
            } else {
                $reponse = Product::where('name', "like", '%' . $name . '%')->get();

                if (!Product::where('name', "like", '%' . $name . '%')->get()->first()) {
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
