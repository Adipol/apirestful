<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Category;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    public function update(Request $request, Product $product, Category $category)
    {
        //sync, attach, syncWithoutDetaching
        //sync=agrega categorias a un producto borrando las anterios categorias relacionadas aal producto
        //attach=agrega las categorias al producto pero repite si se vuelve a introducir
        //syncWithoutDetaching=agrega categorias a un producto sin repetir relaciones

        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }
}
