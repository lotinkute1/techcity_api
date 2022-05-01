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

    private $idOrder = null;
    
    public function index(Request $request){

        $orderController = new OrderController();
        $request->paypal = true;
        $orderController->create($request);
       
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
       

        $listItems = [];
        $totalPrice = 0;

   
       foreach (json_decode($request->order_detail) as $item ) {
      
            $newItem = new Item();
            $itemPrice = (int) ($item->price / 22000);
            $newItem->setName($item->product_name)
                ->setCurrency('USD')
                ->setQuantity($item->number)
                ->setSku(1) 
                ->setPrice($itemPrice );
         $totalPrice += $itemPrice;
            array_push($listItems, $newItem);   
      }





      $amount->setTotal((int) ($totalPrice)); // ^^^^^^^^^
      $amount->setCurrency('USD');

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
  
            $result = $payment->execute($execution, $apiContext);
            return redirect("http://localhost:3000");
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
