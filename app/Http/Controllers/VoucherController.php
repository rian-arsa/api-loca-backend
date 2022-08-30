<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function create(Request $request)
    {
        try {
            $request->all();

            $voucher = Voucher::create([
                'store_id' => $request->store_id,
                'name' => $request->name,
                'kode' => $request->kode,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'minimum' => $request->minimum,
                'kuota' => $request->kuota,
                'deskripsi' => $request->deskripsi,
                'status' => 'AKAN DATANG',
            ]);

            return ResponseFormatter::success(
                $voucher,
                "Voucher Berhasil ditambahkan"
            );
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                [
                    null,
                    'errors' => $th 
                ], "Voucher Gagal ditambahkan", 404
            );
        }
    }

    public function all(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_id = $request->store_id;

        $vouchers = Voucher::query();

        if ($start_date != null) {
            if ($end_date) {
                $vouchers = Voucher::where('store_id', $store_id)->where('start_date', '<=', $start_date)->where('end_date', '>=', $end_date)->orderBy('id', 'DESC')->get();
                return ResponseFormatter::success(
                   $vouchers,
                    'Voucher berhasil diambil 1',
                );
            }
            $vouchers = Voucher::where('store_id', $store_id)->where('start_date', '>', $start_date)->orderBy('id', 'DESC')->get();

            return ResponseFormatter::success(
                $vouchers,
                'Voucher berhasil diambil 2',
            );
        }

        if ($end_date) {
            $vouchers = Voucher::where('store_id', $store_id)->where('end_date', '<', $end_date)->orderBy('id', 'DESC')->get();

           return ResponseFormatter::success(
            $vouchers,
            'Voucher berhasil diambil 3',
        );
        }

    }
}
