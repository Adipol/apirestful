<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;

use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $seller = $buyer->transactions()->with('product.seller')
        ->get()
        ->pluck('product.seller')
        //unique es para que solo obtenga un id unico y no rpetidos
        ->unique('id')
        //values es para eiiminar los espacion ocacionados por el unique y reordenar
        ->values();

        return $this->showAll($seller);
    }
}
