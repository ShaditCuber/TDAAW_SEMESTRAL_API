<?php

namespace App\Http\Controllers;

use App\Models\MovimientoBodega;
use Illuminate\Http\Request;

class MovimientoBodegaController extends Controller
{
   public function create(MovimientoRequest $request)
   {
         $movimiento = new MovimientoBodega();
         $movimiento->product_id = $request->product_id;
         $movimiento->bodega_origen = $request->bodega_origen;
         $movimiento->bodega_destino = $request->bodega_destino;
         $movimiento->cantidad = $request->cantidad;

         if (isset($request->observaciones)) {
              $movimiento->observaciones = $request->observaciones;
         }

         $movimiento->save();
         return response()->json([
              "message" => "Movimiento creado"
         ], 201);
   }

    public function read()
    {
            return response()->json(MovimientoBodega::all(), 200);
    }

    // public function update(MovimientoRequest $request)
    // {
    //         $movimiento = MovimientoBodega::where('id', $request->id)->first();
    //         $movimiento->product_id = $request->product_id;
    //         $movimiento->bodega_origen = $request->bodega_origen;
    //         $movimiento->bodega_destino = $request->bodega_destino;
    //         $movimiento->cantidad = $request->cantidad;

    //         if (isset($request->observaciones)) {
    //              $movimiento->observaciones = $request->observaciones;
    //         }

    //         $movimiento->save();
    //         return response()->json([
    //              "message" => "Movimiento actualizado"
    //         ], 201);
    // }

    public function delete(Request $request)
    {
            if (!MovimientoBodega::where('id', $request->id)->exists()) {
                 return response()->json([
                      "message" => "Movimiento no encontrado"
                 ], 404);
            }

            $movimiento = MovimientoBodega::where('id', $request->id)->first();
            $movimiento->delete();
            return response()->json([
                 "message" => "Movimiento eliminado"
            ], 201);
    }
}
