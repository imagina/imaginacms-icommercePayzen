<?php

use Illuminate\Routing\Router;

Route::prefix('icommercepayzen')->group(function (Router $router){
       
    $router->get('/{eUrl}', [
        'as' => 'icommercepayzen',
        'uses' => 'PublicController@index',
    ]);

    $router->get('/payment/response/{orderId}', [
        'as' => 'icommercepayzen.response',
        'uses' => 'PublicController@response',
    ]);
  
       
});