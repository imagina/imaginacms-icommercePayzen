<?php

use Illuminate\Routing\Router;

Route::prefix('icommercepayzen/v1')->group(function (Router $router) {
    
    $router->get('/', [
        'as' => 'icommercepayzen.api.payzen.init',
        'uses' => 'IcommercePayzenApiController@init',
    ]);

    
    $router->post('/confirmation', [
        'as' => 'icommercepayzen.api.payzen.confirmation',
        'uses' => 'IcommercePayzenApiController@confirmation',
    ]);


});