<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\WarehouseRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ListarWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;


class WarehouseController extends Controller
{
    public function create(WarehouseRequest $request)
    {
        try {
            $warehouse = new Warehouse();

            $warehouse->nombre_bodega = $request->nombre_bodega;
            
            // verificar si el nombre de la bodega ya existe
            $warehouseExist = Warehouse::where('nombre_bodega', '=', $request->nombre_bodega)->first();
            if ($warehouseExist){
                return response()->json([
                    "msg" => "La Bodega ya existe",
                ], Response::HTTP_BAD_REQUEST);
            }

            if (isset($request->descripcion_bodega)
                && $request->descripcion_bodega != null
                && $request->descripcion_bodega != '') {
                $warehouse->descripcion_bodega = $request->descripcion_bodega;
            } else {
                $warehouse->descripcion_bodega = 'No Aplica';
            }

            if (isset($request->direccion_bodega)
                && $request->direccion_bodega != null
                && $request->direccion_bodega != '') {
                $warehouse->direccion_bodega = $request->direccion_bodega;
            } else {
                $warehouse->direccion_bodega = 'Sin Direccion';
            }

            $warehouse->save();

            return response()->json(["msg"=>"Bodega Creada Exitosamente","rps"=>$warehouse], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "linea" => $th->getLine(),
                "file" => $th->getFile(),
                "metodo" => __METHOD__
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function read(ListarWarehouseRequest $request)
    {   
        $columns = ['nombre_bodega', 'descripcion_bodega', 'direccion_bodega','id'];

        try {
            if (isset($request->limit)){
                $warehouses = Warehouse::select($columns)->take($request->limit)->get();
            }
            elseif (isset($request->id)){
                $warehouses = Warehouse::find($request->id);
            }
            elseif (isset($request->nombre_bodega)){
                $warehouses = Warehouse::where('nombre_bodega', 'like', '%'.$request->nombre_bodega.'%')->get();
            }
            elseif (isset($request->direccion_bodega)){
                $warehouses = Warehouse::where('direccion_bodega', 'like', '%'.$request->direccion_bodega.'%')->get();
            }
            else{
                $warehouses = Warehouse::select($columns)->get();
            }
            return response()->json(["msg"=>"Listando", "rsp"=>$warehouses], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "linea" => $th->getLine(),
                "file" => $th->getFile(),
                "metodo" => __METHOD__
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(UpdateWarehouseRequest $request)
    {   
        // verificar si el id viene
        if (!isset($request->id)){
            return response()->json([
                "msg" => "No proporciono el id de la bodega a actualizar",
            ], Response::HTTP_BAD_REQUEST);
        }

        // verificar si la bodega existe
        $warehouseExist = Warehouse::find($request->id);
        if (!$warehouseExist){
            return response()->json([
                "msg" => "La Bodega no existe",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $warehouse = Warehouse::find($request->id);

            if (isset($request->nombre_bodega)
                && $request->nombre_bodega != null
                && $request->nombre_bodega != '') {
                $warehouse->nombre_bodega = $request->nombre_bodega;
            } 

            if (isset($request->descripcion_bodega)
                && $request->descripcion_bodega != null
                && $request->descripcion_bodega != '') {
                $warehouse->descripcion_bodega = $request->descripcion_bodega;
            }

            if (isset($request->direccion_bodega)
                && $request->direccion_bodega != null
                && $request->direccion_bodega != '') {
                $warehouse->direccion_bodega = $request->direccion_bodega;
            }

            $warehouse->save();

            return response()->json(["msg"=>"Bodega Actualizada Correctamente", "rsp"=> $warehouse], 200);
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
        // verificar si el id viene
        if (!isset($request->id)){
            return response()->json([
                "msg" => "No proporciono el id de la bodega a eliminar",
            ], Response::HTTP_BAD_REQUEST);
        }

        // verificar si la bodega existe
        $warehouseExist = Warehouse::find($request->id);
        if (!$warehouseExist){
            return response()->json([
                "msg" => "La Bodega no existe",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $warehouse = Warehouse::find($request->id);

            $warehouse->delete();

            return response()->json(["msg"=>"Bodega Eliminada Correctamente", "rsp"=> $warehouse], 200);
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
