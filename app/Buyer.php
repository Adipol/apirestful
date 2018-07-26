<?php

namespace App;
use App\Transaction;
use App\Scopes\BuyerScope;

//use Illuminate\Database\Eloquent\Model;
//recursos=modelos
class Buyer extends User
{
    protected static function boot()
    {
        //para que laravel inicialize normalmente
        parent::boot();

        static::addGlobalScope(new BuyerScope);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
