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
        $data['image']='i.jpg';
        $data['seller_id']=$seller->id;

        $product =Product::create($data);

        return $this->showOne($product, 201);
    }
}
