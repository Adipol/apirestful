<?php

namespace App;
use App\Transaction;

//use Illuminate\Database\Eloquent\Model;
//recursos=modelos
class Buyer extends User
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
