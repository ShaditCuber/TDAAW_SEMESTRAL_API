<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ListarProductosRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Warehouse;
use App\Http\Resources\ProductResource;



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
            
            // verificar si el nombre del producto ya existe en la boega seleccionada
            $productExist = Product::where('nombre', $request->nombre)
                ->where('warehouse_id', $request->warehouse_id)
                ->first();
            
            if ($productExist){
                return response()->json([
                    "msg" => "El producto ya existe en la bodega seleccionada",
                ], Response::HTTP_BAD_REQUEST);
            }
            

            if (isset($request->descripcion)
                && $request->descripcion != null
                && $request->descripcion != '') {
                $product->descripcion = $request->descripcion;
            } else {
                $product->descripcion = 'No Aplica';
            }

            // $path = $request->imagen-> store('public/products');
            // $path = str_replace('public/', '', $path);
            // $product->imagen = asset('storage/'.$path);

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
    $columns = ['nombre', 'descripcion', 'precio_unitario', 'warehouse_id','id'];
    $perPage = $request->input('per_page', 9); // Default to 15 items per page if not specified

    try {
        if (isset($request->limit)) {
            // Limiting the number of results (might not need pagination)
            $products = Product::select($columns)
                ->take($request->limit)
                ->get();
        } elseif (isset($request->id)) {
            // Fetching a single product
            $products = Product::find($request->id);
        } elseif (isset($request->nombre)) {
            // Search by name with pagination
            $products = Product::select($columns)
                ->where('nombre', 'like', '%'.$request->nombre.'%')
                ->paginate($perPage);
        } elseif (isset($request->precio_unitario)) {
            // Search by unit price with pagination
            $products = Product::select($columns)
                ->where('precio_unitario', '=', $request->precio_unitario)
                ->paginate($perPage);
        } else {
            // Default case with pagination
            $products = Product::select($columns)->paginate($perPage);

            // You might want to add logic here to append stock quantity
        }
        if (isset($products)) {
            return ProductResource::collection($products);
        }

        // Default case with pagination and inventory count
        $products = Product::with('warehouse:name,id') // Select only the name and id from the warehouse
                ->select($columns)
                ->paginate($perPage);
                
        return ProductResource::collection($products);

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
