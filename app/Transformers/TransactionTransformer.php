<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transaction;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identificador'      => (int)$transaction->id,
            'cantidad'           => (string)$transaction->quantity,
            'comprador'          => (string)$transaction->buyer_id,
            'transactiono'           => (int)$transaction->transaction_id,
            'fechaCreacion'      => (string)$transaction->created_at,
            'fechaActualizacion' => (string)$transaction->updated_at,
            'fechaEliminacion'   => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
            //HATEOAS
            'links'=>[            
                [
                    'rel' => 'self',
                    'href'=> route('transactions.show', $transaction->id),
                ],
                [
                    'rel'=>'transaction.categories',
                    'href'=>route('transactions.categories.index',$transaction->id),
                ],
                [
                    'rel'=>'transaction.seller',
                    'href'=>route('transactions.sellers.index',$transaction->id),
                ],
                [
                    'rel'=>'buyer',
                    'href'=>route('buyers.show',$transaction->buyer_id),
                ],
                [
                    'rel'=>'product',
                    'href'=>route('products.show',$transaction->product_id),
                ]
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador'      => 'id',
            'cantidad'           => 'quantity',
            'comprador'          => 'buyer_id',
            'transactiono'           => 'transaction_id',
            'fechaCreacion'      => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion'   => 'deleted_at',
        ];
        
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
