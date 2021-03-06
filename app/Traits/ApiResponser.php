<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

trait ApiResponser
{
  private function successResponse($data, $code)
  {
    return response()->json($data, $code);
  }

  // se creara una funcion protegido para las respuestas
  protected function errorResponse($message, $code)
  {
    return response()->json(['error' => $message, 'code' => $code], $code);
  }

  protected function showAll(Collection $collection, $code = 200)
  {
    if ($collection->isEmpty()) {
      return $this->successResponse(['data' => $collection], $code);
    }

    $transformer = $collection->first()->transformer;
    //se ordena antes de transformar 
    $collection= $this->sortData($collection,$transformer);
    //Paginación
    $collection = $this->paginate($collection);
    $collection = $this->transformData($collection, $transformer);
    $collection = $this->cacheResponse($collection);

    return $this->successResponse($collection, $code);
  }

  protected function showOne(Model $instance, $code = 200)
  {
    $transformer = $instance->transformer;
    $instance = $this->transformData($instance, $transformer);
    
    return $this->successResponse($instance, $code);
  }

  protected function showMessage($message, $code=200)
  {
    return $this->successResponse(['data'=>$message], $code);
  }

  protected function sortData(Collection $collection, $transformer)
  {
      if(request()->has('sort_by')){

          $attribute = $transformer::originalAttribute(request()->sort_by);

          $collection = $collection->sortBy->{$attribute};
      }

      return $collection;
  }

  protected function paginate(Collection $collection)
  {
    $rules = [
      'per_page'=>'integer|min:2|max:50'
    ];

    Validator::validate(request()->all(), $rules);
    //Pagina donde estamos
    $page = LengthAwarePaginator::resolveCurrentPage();
    //cantidad de resultados por pagina
    $perPage = 15;

    if (request()->has('per_page')) {
      $perPage = (int) request()->per_page;
    }
    
    $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

		$paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);
		$paginated->appends(request()->all());

    return $paginated;
  }

  protected function transformData($data, $transformer)
  {
    $transformation = fractal($data, new $transformer);

    return $transformation->toArray();
  }

  //el cache nos ayudara para que la carha se controle 
  protected function cacheResponse($data)
  {
    $url =request()->url();
    //parametros de url
    $queryParams = request()->query();
    //ordena un array de un url
    ksort($queryParams);
    //construir el query string aparatir del array ordenado
    $queryString = http_build_query($queryParams);
    //url completa
		$fullUrl = "{$url}?{$queryString}";

    //Se coloco 15/60 por que se mide en seg=30seg
    return Cache::remember($fullUrl, 15/60, function() use($data){
      return $data;
    });
  } 
}