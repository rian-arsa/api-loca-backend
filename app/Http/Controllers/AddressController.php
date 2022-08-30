<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Addresses;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create(Request $request)
    {
        try {
            $request->validate([
                'label' => 'required|string',
                'users_id' => 'required',
                'complete_address' => 'required|string',
                'address_details' => 'required|string',
                'name' => 'string|nullable',
                'address' => 'string|nullable',
                'phone' => 'numeric|nullable',
                'choice' => 'numeric'
            ]);
    
            $addressAkun = Addresses::where('users_id', $request->users_id)->get();
    
            if ($request->choice == 1) {
                if ($addressAkun) {
                    Addresses::where('users_id', $request->users_id)
                            ->update(['choice' => 0]);
                }
            }
    
            $address = Addresses::create([
                'users_id' =>$request->users_id,
                'choice' => $request->choice,
                'label' => $request->label,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'complete_address' => $request->complete_address,
                'address_details' => $request->address_details,
            ]);

            return ResponseFormatter::success($address, 'Alamat Berhasil Ditambahkan');
        } catch (\Throwable $th) {
            print($th);
            return ResponseFormatter::error(null, "Alamat Gagal Ditambahkan", 404);
        }
    }

    public function all(Request $request)
    {
        $id = $request->input('id');
        $users_id = $request->users_id;
        $limit = $request->input('limit');
        $name = $request->input('name');
        $start_date = $request->start_date; 
        $end_date = $request->end_date; 

        if ($id) {
            $address = Addresses::find($id);

            if ($address) {
                return ResponseFormatter::success(
                    $address,
                    'Data kategori berhasil diambil',
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data kategori tidak ada',
                    404,
                );
            }
        }

        $address = Addresses::query();

        if ($name) {
            $address->where('name', 'like', '$' . $name . '$')->where('users_id', $users_id);
        }

        if ($users_id) {
            $address->where('users_id', $users_id);
        }

        return ResponseFormatter::success(
            [
                'address' => $address->paginate($limit)
            ],
            'Data Address Berhasil Diambil'
        );
    }

    public function edit(Request $request)
    {
        $request->all();

        $request->users_id = $request->users_id;
        
        $address = Addresses::find($request->id);

        $address->label =  $request->label;
        $address->name =  $request->name;
        $address->phone =  $request->phone;
        $address->address =  $request->address;
        $address->complete_address =  $request->complete_address;
        $address->address_details =  $request->address_details;

        $addressAkun = Addresses::where('users_id', $request->users_id)->get();
        if ($request->choice == 1) {
            if ($addressAkun) {
                Addresses::where('users_id', $request->users_id)
                        ->update(['choice' => 0]);
            }
            $address->choice =  $request->choice;
        } else {
            $address->choice =  $request->choice;
        }

        return ResponseFormatter::success($address, 'Alamat Berhasil Diubah');
    }

    public function delete($id)
    {
        $address = Addresses::find($id);
 
        $address->delete();

        return ResponseFormatter::success(
            $address,
            'Berhasil Menghapus Alamat',
        );
    }
}
