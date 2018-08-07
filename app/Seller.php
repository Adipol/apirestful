<?php

namespace App;
use App\Product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

//use Illuminate\Database\Eloquent\Model;

//se extiende de User y no de Model
class Seller extends User
{
    public $transformer = SellerTransformer::class;

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
