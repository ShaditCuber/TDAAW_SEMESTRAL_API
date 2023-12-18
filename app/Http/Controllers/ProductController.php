<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ListarProductosRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Warehouse;



class ProductController extends Controller
{

   
    public function create(ProductRequest $request)
    {   


        if (isset($request->warehouse_id)){
            $warehouse = Warehouse::find($request->warehouse_id);
            if (!$warehouse){
                return response()->json([
                    "msg" => "La bodega no existe",
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        try {
           $product = new Product();

            $product->nombre = $request->nombre;
            
            // verificar si el nombre del producto ya existe
            $productExist = Product::where('nombre', '=', $request->nombre)->first();
            if ($productExist){
                return response()->json([
                    "msg" => "El Producto ya existe",
                ], Response::HTTP_BAD_REQUEST);
            }

            if (isset($request->descripcion)
                && $request->descripcion != null
                && $request->descripcion != '') {
                $product->descripcion = $request->descripcion;
            } else {
                $product->descripcion = 'No Aplica';
            }

            $path = $request->imagen-> store('public/products');
            $path = str_replace('public/', '', $path);
            $product->imagen = asset('storage/'.$path);

            $product->precio_unitario = $request->precio_unitario;
            $product->warehouse_id = $request->warehouse_id;

            $product->save();

            return response()->json(["msg"=>"Producto Creado Correctamente", "rsp"=> $product], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "linea" => $th->getLine(),
                "file" => $th->getFile(),
                "metodo" => __METHOD__
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function read(ListarProductosRequest $request)
    {   
        $columns = ['nombre', 'descripcion', 'precio_unitario', 'warehouse_id','imagen','id'];

        try {
            if (isset($request->limit)){
                $products = Product::select($columns)
                ->take($request->limit)
                ->get();
            }elseif (isset($request->id)){
                $products = Product::find($request->id);
            }
            elseif (isset($request->nombre)){
                $products = Product::select($columns)
                ->where('nombre', 'like', '%'.$request->nombre.'%')
                ->get();
            }
            elseif (isset($request->precio_unitario)){
                $products = Product::select($columns)
                ->where('precio_unitario', '=', $request->precio_unitario)
                ->get();
            }
            else{
                $products = Product::select($columns)
                ->get();

                // AGREGARLE LA CANTIDAD DE CADA UNO QUE HAY EN STOCK
            }
            return response()->json(["msg"=>"Listando", "rsp"=>$products], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "linea" => $th->getLine(),
                "file" => $th->getFile(),
                "metodo" => __METHOD__
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(UpdateProductRequest $request)
    {   
        if (!isset($request->id)){
            return response()->json([
                "msg" => "No proporciono el id del producto a actualizar",
            ], Response::HTTP_BAD_REQUEST);
        }

        // verificar que el producto exista
        $product = Product::find($request->id);
        if (!$product){
            return response()->json([
                "msg" => "El Producto no existe",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $product = Product::find($request->id);

            // si viene la varuabe se actualiza
            if (isset($request->nombre)){
                $product->nombre = $request->nombre;
            }
            if (isset($request->descripcion)){
                $product->descripcion = $request->descripcion;
            }
            if (isset($request->precio_unitario)){
                $product->precio_unitario = $request->precio_unitario;
            }
            
            // if (isset($request->imagen)){
            //     $path = $request->imagen-> store('public/products');
            //     $path = str_replace('public/', '', $path);
            //     $product->imagen = asset('storage/'.$path);
            // }

            $product->save();

            return response()->json(["msg"=>"Producto Actualizado Correctamente", "rsp"=>$product], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "linea" => $th->getLine(),
                "file" => $th->getFile(),
                "metodo" => __METHOD__
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(Request $request)
    {   
         if (!isset($request->id)){
            return response()->json([
                "msg" => "No proporciono el id del producto a eliminar",
            ], Response::HTTP_BAD_REQUEST);
        }

        // verificar que el producto exista
        $product = Product::find($request->id);
        if (!$product){
            return response()->json([
                "msg" => "El Producto no existe",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $product = Product::find($request->id);

            $product->delete();

            return response()->json(["msg" =>"Producto eliminado correctamente"], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "linea" => $th->getLine(),
                "file" => $th->getFile(),
                "metodo" => __METHOD__
            ], Response::HTTP_BAD_REQUEST);
        }
    }
   
    public function restore(Request $request)
    {
        if (!isset($request->id)){
            return response()->json([
                "msg" => "No proporciono el id del producto a restaurar",
            ], Response::HTTP_BAD_REQUEST);
        }

        // verificar que el producto exista
        $product = Product::withTrashed()->find($request->id);
        if (!$product){
            return response()->json([
                "msg" => "El Producto no existe",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $product = Product::withTrashed()->find($request->id);

            $product->restore();

            return response()->json(["msg"=> "Producto Restablecido correctamente" , "rsp" => $product], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "linea" => $th->getLine(),
                "file" => $th->getFile(),
                "metodo" => __METHOD__
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    
}
