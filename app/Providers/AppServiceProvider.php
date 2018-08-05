<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\userCreated;
use App\User;
use App\Mail\userMailChanged;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //el helper retry se encargara de reenviar el proceso eneste caso 5 veces y los invervalos seran de 100ms
        User::created(function($user){
            retry(5, function() use($user){
                Mail::to($user)->send(new userCreated($user)); 
            }, 100);      
        });

        User::updated(function($user){
            //si isDirty() esta vacio comprueba cada atributo si hubo almenos uno que se modifico
            if ($user->isDirty('email')) {
                retry(5, function() use($user){
                    Mail::to($user)->send(new userMailChanged($user));
                }, 100);
            }
        });

        Product::updated(function($product){
            if($product->quantity == 0 && $product->estaDisponible()) {
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
