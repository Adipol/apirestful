<?php

namespace App;
use App\Transaction;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

//use Illuminate\Database\Eloquent\Model;
//recursos=modelos
class Buyer extends User
{
    public $transformer = BuyerTransformer::class;

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
