<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Courier;
use App\Models\Day;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darbaoui\Avatar\Facades\Avatar;

class StoreController extends Controller
{
    public function create(Request $request) 
    {
        try {
            $request->validate([
                'users_id' => 'required',
                'name' => 'required|string|max:255',
                'username' => 'required|unique:stores',
                'address' => 'required|string',
            ]);

            $profile = "StoreImage/image_profile_toko.png";
    
            $store = Store::create([
                'users_id' => $request->users_id,
                'name' => $request->name,
                'username' => $request->username,
                'address' => $request->address,
                'profile' => $profile,
            ]);

            $courier = Courier::create([
                'jne_kilat' => false,
                'sicepat_kilat' => false,
                'jnt_kilat' => false,
                'jne_reguler' =>false,
                'sicepat_reguler' => false,
                'jnt_reguler' => false,
                'jne_ekonomis' => false,
                'sicepat_ekonomis' => false,
                'jnt_ekonomis' => false,
                'jne_kargo' => false,
                'sicepat_kargo' => false,
                'jnt_kargo' => false,
            ]);

            $day = Day::create([
                'senin' => 'false',
                'selasa' => 'false',
                'rabu' => 'false',
                'kamis' => 'false',
                'jumat' => 'false',
                'sabtu' => 'false',
                'minggu' => 'false',
            ]);

            $store->couriers_id = $courier->id;
            $store->day_id = $day->id;
            $store->save();
            
            return ResponseFormatter::success(
                $store,
                'Store Berhasil ditambahkan',
            );
        } catch (Exception $error) {
            return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => $error,
                ], 'Store Gagal ditambahkan', 500
            );
        }
    }

    public function show(Request $request)
    {
        $id = $request->id;

        $store = Store::where('users_id', $id)->first();

        if ($store) {
            return ResponseFormatter::success(
                $store,
                'Store Berhasil',
            );
        } else {
            return ResponseFormatter::error(
                null, 'Store Gagal', 404
        );
        }
        
    }

    public function update(Request $request){
        try {
            $request->all();

            $store = Store::where('id', $request->id)->first();
            if($request->username != null) {
                $request->validate([
                    'username' => 'unique:stores',
                ]);
                $username = $request->username;
            } else {
                $username = $store['username'];
            }

            if ($request->hasFile('profile')) {
                $request->validate([
                    'profile' => 'mimes:jpeg,jpg,png,gif',
                ]);
                // unlink(storage_path('app/'.$store['profile']));
                $path = $request->file('profile')->store('StoreImage');
            } else {
                $path = $store['profile'];
            }

            if ($request->name != null) {
                $name = $request->name;
            } else {
                $name = $store['name'];
            }

            if ($request->deskripsi != null) {
                $deskripsi = $request->deskripsi;
            } else {
                $deskripsi = $store['deskripsi'];
            }

            if ($request->catatan_toko != null) {
                $catatan_toko = $request->catatan_toko;
            } else {
                $catatan_toko = $store['catatan_toko'];
            }
            

            $store->name = $name;
            $store->username = $username;
            $store->profile = $path;
            $store->deskripsi = $deskripsi;
            $store->catatan_toko = $catatan_toko;

            $store->save();

            return ResponseFormatter::success(
                $store,
                'Store Berhasil di update',
            );

        } catch (Exception $error) {
            return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => $error,
                ], 'Store Gagal di update', 500
            );
        }
    }
}
