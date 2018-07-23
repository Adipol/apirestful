<?php

namespace App;
use App\Product;

//use Illuminate\Database\Eloquent\Model;

//se extiende de User y no de Model
class Seller extends User
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
