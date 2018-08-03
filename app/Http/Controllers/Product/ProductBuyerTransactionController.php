<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use App\Transaction;
use App\User;

class ProductBuyerTransactionController extends ApiController
{
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules=[
            'quantity'=>'required|integer|min:1',
        ];

        $this->validate($request,$rules);
        //verificar que el comprador y el vededor sean diferentes
        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse('El comprador debe ser diferente al vendedor.', 409);
        }

        //verificar al comprador y vendedor
        if (!$buyer->esVerificado()) {
            return $this->errorResponse('el comprador debe ser un usuario verificado.', 409);
        }

        if (!$product->seller->esVerificado()) {
            return $this->errorResponse('el vendedor debe ser un usuario verificado.', 409);
        }

        if(!$product->estaDisponible()){
            return $this->errorResponse('El producto para esta transaccion no esta disponible.', 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('El producto no tiene la cantidad disponible requerida para esta transaccion.');
        }

        return DB::transaction(function() use($request,$product,$buyer){
            $product->quantity = $product->quantity - $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id'=> $buyer->id,
                'product_id'=> $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}
