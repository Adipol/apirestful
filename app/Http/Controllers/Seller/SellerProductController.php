<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Product;
use App\User;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }
    //se pudo User en vez de Seller por un nuevo usuario que no este como seller
    public function store(Request $request, User $seller)
    {
        $rules =[
            'name' => 'required',
            'description' => 'required',
            'quantity'=>'required|integer|min:1',
            'imagen'=>'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['status']= Product::PRODUCTO_NO_DISPONIBLE;
        $data['image']=$request->image->store('');
        $data['seller_id']=$seller->id;

        $product =Product::create($data);

        return $this->showOne($product, 201);
    }

    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules =[
            'quantity'=> 'integer|min:1',
            'status'=>'in: ' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE,            
        ];

        $this->validate($request, $rules);
        $this->verificarVendedor($seller, $product);

        $product->fill($request->only(
            ['name',
            'description',
            'quantity',]
        ));

        if ($request->has('status')) {
            $product->status= $request->status;
            if($product->estaDisponible() && $product->categories()->count() == 0) {
                return $this->errorResponse('Un producto activo debe tener al menos uan categoria.' , 409);
            }
        }

        if ($product->isClean()) {
            return $this->errorResponse('se debe especificar al menos un valor diferente para actualizar.', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    public function destroy(Seller $seller,Product $product)
    {
        $this->verificarVendedor($seller, $product);

        $product->delete();

        return $this->showOne($product);
    }

    protected function verificarVendedor(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id){
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto.');
        }
    }
}

