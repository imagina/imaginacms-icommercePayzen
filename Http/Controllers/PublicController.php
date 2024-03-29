<?php

namespace Modules\Icommercepayzen\Http\Controllers;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base
use Modules\Core\Http\Controllers\BasePublicController;

use Modules\Icommerce\Repositories\TransactionRepository;
use Modules\Icommerce\Repositories\OrderRepository;

// Services
use Modules\Icommercepayzen\Services\PayzenService;

class PublicController extends BasePublicController
{

    private $order;
    private $transaction;
    private $payzenService;
    private $log = "Icommercepayzen: PublicController|";

    public function __construct(
        OrderRepository $order,
        TransactionRepository $transaction,
        PayzenService $payzenService
    )
    {
        $this->order = $order;
        $this->transaction = $transaction;
        $this->payzenService = $payzenService;
    }


    /**
     * Index data
     * @param Requests request
     * @return route
     */
    public function index($eURL)
    {

        try {

            // Decr
            $infor = paymentezDecriptUrl($eURL);
            $orderID = $infor[0];
            $transactionID = $infor[1];

            // Validate get data
            $order = $this->order->find($orderID);
            $transaction = $this->transaction->find($transactionID);
           
            //Create Base Payzen
            $payzen = $this->payzenService->create($order,$transaction);

            //Final
            $payzen->executeRedirection();

        } catch (\Exception $e) {

            \Log::error($this->log.'index|Message'.$e->getMessage());
            \Log::error($this->log.'index|Code'.$e->getCode());

            //Message Error
            $status = 500;
            $response = ['errors' => $e->getMessage(),'code' => $e->getCode()];

            return redirect()->route("homepage");

        }
       

    }

    /**
    * Response Frontend After the Payment
    * @param  $request 
    * @param  $orderId
    * @return redirect
    */
    public function response(Request $request,$orderId)
    {

        $isQuasarAPP = env("QUASAR_APP", false);

        //Get all data from request
        $data = $request->all();

        if(isset($data['vads_order_id'])){

            $order = $this->order->find($orderId);
            if(!$isQuasarAPP){
                if (!empty($order))
                    return redirect($order->url);
                else
                    return redirect()->route('home');

            }else{
                return view('icommerce::frontend.orders.closeWindow');
            }

        }else{

          if(!$isQuasarAPP){
            return redirect()->route('homepage');
          }else{
            return view('icommerce::frontend.orders.closeWindow');
          }

        }

    }


}
