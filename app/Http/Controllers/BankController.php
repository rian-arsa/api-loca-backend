<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'rekening' => 'required|unique:banks',
                'bank_name' => 'required',
                'users_id' => 'required',
                'choice' => 'required',
            ]);

            $bankUser = Bank::where('users_id', $request->users_id)->get();

            if ($request->choice == 1) {
                if ($bankUser) {
                    Bank::where('users_id', $request->users_id)
                        ->update(['choice' => 0]);
                }
            }
    
            $bank = Bank::create([
                'users_id' => $request->users_id,
                'name' => $request->name,
                'rekening' => $request->rekening,
                'bank_name' => $request->bank_name,
                'choice' => $request->choice,
            ]);
        
            return ResponseFormatter::success(
                $bank,
                'Bank Berhasil Ditambahkan'
            );
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something Went Wrong',
                    'errors' => $th,
                ],
                'Bank Gagal Ditambahkan', 404,
            );
        };
    }

    public function all(Request $request)
    {
        $id = $request->input('id');
        $users_id = $request->users_id;
        $limit = $request->input('limit');
        $name = $request->input('name');

        if ($id) {
            $bank = Bank::find($id);

            if ($bank) {
                return ResponseFormatter::success(
                    $bank,
                    'Data Bank berhasil diambil',
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Bank tidak ada',
                    404,
                );
            }
        }

        $bank = Bank::query();

        if ($name) {
            $bank->where('name', 'like', '$' . $name . '$');
        }

        if ($users_id) {
            $bank->where('users_id', $users_id);
        }

        return ResponseFormatter::success(
            [
                'bank' => $bank->paginate($limit)
            ],
            'Data Bank Berhasil Diambil'
        );
    }

    public function edit(Request $request)
    {
        $request->all();
        
        $bank = Bank::find($request->id);

        $bank->name = $request->name;
        $bank->rekening = $request->rekening;
        $bank->bank_name = $request->bank_name;
        $bank->users_id = $request->users_id;

        
        $bankUser = Bank::where('users_id', $request->users_id)->get();
        if ($request->choice == 1) {
            if ($bankUser) {
                Bank::where('users_id', $request->users_id)
                    ->update(['choice' => 0]);
            }
            $bank->choice = $request->choice;
        } else {
            $bank->choice = $request->choice;
        }

        $bank->save();

        return ResponseFormatter::success(
            $bank,
            'Berhasil Mengubah Bank',
        );

    }

    public function delete($id)
    {
        $category = Bank::find($id);
 
        $category->delete();

        return ResponseFormatter::success(
            $category,
            'Berhasil Menghapus Bank',
        );
    }
}
