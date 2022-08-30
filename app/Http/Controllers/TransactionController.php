<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->id;
        $limit = $request->input('limit', 6);
        $status = $request->status;
        $store_id = $request->store_id;

        if ($id) {
            $transaction = Transaction::with(['user', 'store', 'metodePembayaran', 'details'])->find($id);

            if ($transaction) {
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
            }
        }

        $transaction = Transaction::with(['user', 'store', 'metodePembayaran', 'details', 'details.product'])->where('store_id', $store_id);

        if ($status) {
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data list transaksi berhasil diambil'
        );
    }

    public function create(Request $request)
    {
        try {
            $request->all();

            $transaction = Transaction::create([
                'users_id' =>  $request->users_id,
                'store_id' => $request->store_id,
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                
                // 'invoice' => 'INV'. Carbon::now().random_int(100000, 999999). $request->users_id.$request->store_id,
                // 'nomor_resi' => $request->nomor_resi,
                'jasa_antar' => $request->jasa_antar,
                
                'total_shop' => $request->total_shop,
                'diskon_price' => $request->diskon_price,
                'shipping_price' => $request->shipping_price,
                'shipping_diskon' => $request->shipping_diskon,
                'total_price' => $request->total_price,
                
                'status' => $request->status,
                
                'catatan' => $request->catatan,
            ]);

            foreach ($request->items as $product) {
                TransactionDetail::create([
                    'users_id' => $request->users_id,
                    'products_id' => $product['id'],
                    'transactions_id' => $transaction->id,
                    'quantity' => $product['quantity']
                ]);
            }

            return ResponseFormatter::success(
                $transaction->load('user', 'store', 'metodePembayaran', 'details', 'details.product'),
                "Transaksi Berhasil ditambahkan"
            );
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                [
                    null,
                    'errors' => $th 
                ], "Transaksi Gagal ditambahkan", 404
            );
        }
    }

    public function edit(Request $request)
    {
        $transaction = Transaction::find($request->id);

        $transaction->id = $transaction->id;
        $transaction->store_id = $transaction->store_id;
        $transaction->metode_pembayaran_id = $transaction->metode_pembayaran_id;


        if ($transaction->invoice == null) {
            $transaction->invoice = 'INV'. Carbon::createFromFormat('Y-m-d H:i:s', now())->format('dmYHis').random_int(100000, 999999). $request->users_id.$request->store_id;
        } else {
            $transaction->invoice = $transaction->invoice;
        }

        if ($request->nomor_resi != null) {
            $transaction->nomor_resi = $request->nomor_resi;
        } else {
            $transaction->nomor_resi = $transaction->nomor_resi;
        }

        $transaction->total_shop = $transaction->total_shop;
        $transaction->diskon_price = $transaction->diskon_price;
        $transaction->shipping_price = $transaction->shipping_price;
        $transaction->shipping_diskon = $transaction->shipping_diskon;
        $transaction->total_price = $transaction->total_price;

        if ($request->status != null) {
            $transaction->status = $request->status;
        } else {
            $transaction->status = $transaction->status;
        }
        
        $transaction->catatan = $transaction->catatan;

        $transaction->save();

        return ResponseFormatter::success(
            $transaction->load('user', 'store', 'metodePembayaran', 'details', 'details.product'),
            "Transaksi Berhasil ditambahkan"
        );
    }
}
