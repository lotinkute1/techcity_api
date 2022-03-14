<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\InputFields;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\PaymentExecution;
use PayPal\Api\WebProfile;

class PaypalController extends Controller
{
    public function index(Request $request){
      
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AfY4wjJ74PheYPwSKGrF2PUEJwovVUtrH8YboF1AreIihWvmRzUqt1z5EyKCqt3GqkqSfXFObn6qUiw_',     // ClientID
                'EE__DS5MYKsuxZe1QX3MCtafaY3nozd3MfNNnO2QRxVjt68H84DxIjwvgeRA5CCPSdGe0RwUeI4h6lnC'      // ClientSecret
            )
        );
        // After Step 2
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        // truy van cart

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal(50); // ^^^^^^^^^
        $amount->setCurrency('USD');

        $listItems = [];
       // foreach ($carts as $item ) {
            $newItem = new Item();
            $newItem->setName("san pham 1")
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku(1) 
                ->setPrice(50);
            array_push($listItems, $newItem);   
       // }
        $itemList = new ItemList();
        $itemList->setItems($listItems);


        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
        $transaction->setItemList($itemList);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal_return'))
            ->setCancelUrl(route('paypal_cancel'));

        // Add NO SHIPPING OPTION
        $inputFields = new InputFields();
        $inputFields->setNoShipping(1);

        $webProfile = new WebProfile();
        $webProfile->setName('Lap store' . uniqid())->setInputFields($inputFields);

        $webProfileId = $webProfile->create($apiContext)->getId();

        $payment = new \PayPal\Api\Payment();
        $payment->setExperienceProfileId($webProfileId); // no shipping
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);
        
        try {
            $payment->create($apiContext);
            echo $payment;
            echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
            return redirect($payment->getApprovalLink());
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }
    public function paypalReturn(){
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AfY4wjJ74PheYPwSKGrF2PUEJwovVUtrH8YboF1AreIihWvmRzUqt1z5EyKCqt3GqkqSfXFObn6qUiw_',     // ClientID
                'EE__DS5MYKsuxZe1QX3MCtafaY3nozd3MfNNnO2QRxVjt68H84DxIjwvgeRA5CCPSdGe0RwUeI4h6lnC'      // ClientSecret
            )
        );

        $paymentId = $_GET['paymentId'];
        $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
        $payerId = $_GET['PayerID'];

       // Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
          //  $email = Auth::user()->email;
            $result = $payment->execute($execution, $apiContext);
          /*  $carts = Cart::where("email",$email)->get();
            foreach($carts as $item) {
                Order::create([
                    "email" => $item["email"],
                    "idProduct" => $item["idProduct"],
                    "quantity" => $item["quantity"],
                    "totalPrice" => $item["totalPrice"],
                    "status" => "Paid"
                ]);
                $product = products::find($item["idProduct"]);
                $product->selled = $product->selled + $item["quantity"];
                $product->quantityRemain = $product->quantityRemain - $item["quantity"];
                $product->save();
            }
            Cart::where("email",$email)->delete();
            // send mail
         
            $data = array("carts" => $carts);
            Mail::send("vendor.mail.orderDone",$data,function($message){
                $message->to(Auth::user()->email)->subject("Payment order successful!");
                $message->from("skygamershop102@gmail.com","Skygamer shop");
            }); */
            return redirect('checkout')->with(["status"=>"Payment order successful. Please check your email !"]);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        }
    }
    public function paypalCancel(){
        return "order canceled";
    }
}
