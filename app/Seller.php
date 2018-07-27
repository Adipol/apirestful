<?php

namespace App;
use App\Product;
use App\Scopes\SellerScope;

//use Illuminate\Database\Eloquent\Model;

//se extiende de User y no de Model
class Seller extends User
{
    protected static function boot()
    {
        //para que laravel inicialize normalmente
        parent::boot();

        static::addGlobalScope(new SellerScope);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
