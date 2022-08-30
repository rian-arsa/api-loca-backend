<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Courier;
use App\Models\Couriers;
use App\Models\Store;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function create(Request $request)
    {
        try {
            $request->all();
    
            $store = Store::where('id', $request->stores_id)->first();

            $courier = Courier::where('id', $store->couriers_id)->first();

            $courier->jne_kilat = $request->jne_kilat;
            $courier->jne_reguler = $request->jne_reguler;
            $courier->jne_ekonomis = $request->jne_ekonomis;
            $courier->jne_kargo = $request->jne_kargo;
            $courier->sicepat_kilat = $request->sicepat_kilat;
            $courier->sicepat_reguler = $request->sicepat_reguler;
            $courier->sicepat_ekonomis = $request->sicepat_ekonomis;
            $courier->sicepat_kargo = $request->sicepat_kargo;
            $courier->jnt_kilat = $request->jnt_kilat;
            $courier->jnt_reguler = $request->jnt_reguler;
            $courier->jnt_ekonomis = $request->jnt_ekonomis;
            $courier->jnt_kargo = $request->jnt_kargo;

            $courier->save();
            
            return ResponseFormatter::success(
                ["courier" => $courier],
                "Courier berhasil ditambahkan"
            );
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Courier gagal ditambahkan',
                404,
            );
        }
    }

    public function show(Request $request)
    {
        try {
            $request->all();
            $store = Store::where('id', $request->id)->first();
            
            $courier = Courier::where('id', $store->couriers_id)->first();
            
            return ResponseFormatter::success(
                ["courier" => $courier],
                'Courier berhasil',
            );
        } catch (\Throwable $th) {
           return ResponseFormatter::error(
                throw $th,
                'Courier Tidak Ada',
                404,
            );
        }
    } 
}
