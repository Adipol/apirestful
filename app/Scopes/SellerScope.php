<?php
namespace App\Scopes;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SellerScope implements Scope
{
    public function apply(Builder $builer, Model $model)
    {
        $builder->has('products');
    }
}