<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Requests\StockRequest; 
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ListarStockRequest;
use App\Models\Product;


class StockController extends Controller
{
    public function create(StockRequest $request)
    {
        $stock = new Stock();
        $stock->product_id = $request->product_id;
        $stock->cantidad = $request->cantidad;
        $stock->tipo = $request->tipo;

        if(isset($request->observaciones)){
            $stock->observaciones = $request->observaciones;
        }

        $stock->save();

        return response()->json([
            "msg" => "Stock creado",
            "stock" => $stock
        ], Response::HTTP_CREATED);
    }

    public function read(ListarStockRequest $request)
    {   
        $columns = [
        'stocks.id', 
        'stocks.product_id', 
        'stocks.cantidad', 
        'stocks.observaciones', 
        'stocks.tipo'
        ];
       
        try {

            if (isset($request->product_id)) {
            // Obtén los detalles del producto y calcula la cantidad total
            $productDetails = Product::where('id', $request->product_id)
                ->select('id', 'nombre', 'descripcion', 'precio_unitario', 'warehouse_id', 'imagen')
                ->with(['stocks' => function($query) {
                    $query->selectRaw('product_id, SUM(CASE WHEN tipo = "entrada" THEN cantidad ELSE -cantidad END) as total')
                          ->groupBy('product_id');
                }])
                ->first();

            // Añade la cantidad total de stock directamente al resultado
            if ($productDetails && $productDetails->stocks->isNotEmpty()) {
                $productDetails->stock = $productDetails->stocks->first()->total;
                unset($productDetails->stocks); // Elimina el array stocks si no es necesario
            }

            return response()->json(["msg" => "Detalles de Producto", "rsp" => $productDetails], 200);
        }

             $query = Stock::select($columns)
                ->join('products', 'stocks.product_id', '=', 'products.id');

            if (isset($request->limit)){
                $query->take($request->limit);
            }
            
            if (isset($request->tipo)){
                $query->where('stocks.tipo', '=', $request->tipo);
            }
            if (isset($request->warehouse_id)){
                $query->where('products.warehouse_id', '=', $request->warehouse_id);
            }

            $stock = $query->get();

            return response()->json(["msg"=>"Listando", "rsp"=>$stock], 200);
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
                "msg" => "No proporciono el id del stock a eliminar",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $stock = Stock::find($request->id);
            $stock->delete();

            return response()->json(["msg"=>"Stock Eliminado"], 200);
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
