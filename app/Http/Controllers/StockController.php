<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Requests\StockRequest; 
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ListarStockRequest;
use App\Models\Product;
use Carbon\Carbon;

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
            'stocks.tipo',
            'stocks.created_at',
            'products.nombre as product_nombre', 
            'products.descripcion as product_descripcion',
            'products.precio_unitario as product_precio_unitario',
        ];
        $perPage = $request->input('per_page', 10);

        try {
            if (isset($request->product_id)) {
                $productDetails = Product::where('id', $request->product_id)
                    ->select('id', 'nombre', 'descripcion', 'precio_unitario', 'warehouse_id')
                    ->with(['stocks' => function($query) {
                        $query->select('id', 'product_id', 'cantidad', 'observaciones', 'tipo', 'created_at')
                            ->orderBy('created_at', 'desc');
                    }])
                    ->first();
                    
                if ($productDetails && $productDetails->stocks->isNotEmpty()) {
                    $totalStock = $productDetails->stocks->sum(function($stock) {
                        return $stock->tipo === 'entrada' ? $stock->cantidad : -$stock->cantidad;
                    });
                    $totalAmount = $productDetails->stocks->sum(function($stock) use ($productDetails) {
                        return ($stock->tipo === 'entrada' ? $stock->cantidad : -$stock->cantidad) * $productDetails->precio_unitario;
                    }); 
                    foreach ($productDetails->stocks as $stock) {
                        $stock->created_at_formatted = Carbon::parse($stock->created_at)->format('d-m-Y');
                    }

                    $productDetails->total_stock = $totalStock;
                    $productDetails->total_amount = $totalAmount;
                }

                

                return response()->json(["msg" => "Detalles de Producto con Historial", "rsp" => $productDetails], 200);
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

            $stock = $query->paginate($perPage);

            return response()->json(["msg" => "Listando Stock", "rsp" => $stock], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
                "file" => $th->getFile(),
            ], 400);
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


    public function resume(Request $request){
        try {
            $totalProducts = Product::count();

            $formatSales = function ($amount) {
                return is_null($amount) ? '$0' : '$' . number_format($amount, 0, ',', '.');
            };

            $totalSales = Stock::join('products', 'stocks.product_id', '=', 'products.id')
                            ->where('stocks.tipo', 'salida')
                            ->selectRaw('SUM(stocks.cantidad * products.precio_unitario) as total_sales_amount')
                            ->first()
                            ->total_sales_amount;

            $totalSales = $formatSales($totalSales);
            $salesYesterday = Stock::join('products', 'stocks.product_id', '=', 'products.id')
                                ->where('stocks.tipo', 'salida')
                                ->whereDate('stocks.created_at', '=', now()->subDay())
                                ->selectRaw('SUM(stocks.cantidad * products.precio_unitario) as yesterday_sales')
                                ->first()
                                ->yesterday_sales;
            $salesYesterday = $formatSales($salesYesterday);
            $salesToday = Stock::join('products', 'stocks.product_id', '=', 'products.id')
                            ->where('stocks.tipo', 'salida')
                            ->whereDate('stocks.created_at', '=', now())
                            ->selectRaw('SUM(stocks.cantidad * products.precio_unitario) as today_sales')
                            ->first()
                            ->today_sales;
            $salesToday = $formatSales($salesToday);

            return response()->json([
                "total_products" => $totalProducts,
                "total_sales_amount" => $totalSales,
                "yesterday_sales" => $salesYesterday,
                "today_sales" => $salesToday
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
                "file" => $th->getFile(),
            ], 400);
        }
    }


}
