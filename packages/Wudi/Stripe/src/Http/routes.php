<?php

Route::group([
     'prefix'     => 'stripe',
       'middleware' => ['web', 'theme', 'locale', 'currency']
   ], function () {

       Route::get('stripe-redirect','Wudi\Stripe\Http\Controllers\StripeController@redirect')->name('stripe.process');
       Route::get('stripe-success','Wudi\Stripe\Http\Controllers\StripeController@success')->name('stripe.success'); 
       Route::get('stripe-cancel','Wudi\Stripe\Http\Controllers\StripeController@failure')->name('stripe.cancel'); 
       Route::post('stripe-create', 'Wudi\Stripe\Http\Controllers\StripeController@customCreate')->name('stripe.create'); 
       Route::get('stripe-payview', 'Wudi\Stripe\Http\Controllers\StripeController@customPayview')->name('stripe.payview'); 
});