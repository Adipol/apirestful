<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compradores = Buyer::has('transactions')->get();

        //return response()->json(['data'=>$compradores],200);
        //Uso del trait
        return $this->showAll($compradores);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        /* $comprador = Buyer::has('transactions')->findOrfail($id); */

        return $this->showOne($buyer);
    }
}
