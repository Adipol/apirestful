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
            'producto'           => (int)$transaction->product_id,
            'fechaCreacion'      => (string)$transaction->created_at,
            'fechaActualizacion' => (string)$transaction->updated_at,
            'fechaEliminacion'   => isset($transaction->updated_at) ? (string) $transaction->deleted_at : null,
        ];
    }
}