<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories')
        //colapsa una colección de matrices en una sola colección plana
        ->collapse()
        //unique es para que solo obtenga un id unico y no rpetidos
        ->unique('id')
        //values es para eiiminar los espacios u objetos vacios por el unique y reordenar
        ->values();

        return $this->showAll($categories);
    }
}
