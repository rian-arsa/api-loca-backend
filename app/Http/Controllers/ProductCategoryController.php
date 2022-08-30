<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $stores_id = $request->stores_id;
        $limit = $request->input('limit');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if ($id) {
            $category = ProductCategory::with('products')->find($id);

            if ($category) {
                return ResponseFormatter::success(
                    $category,
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

        $category = ProductCategory::query();

        if ($name) {
            $category->where('name', 'like', '$' . $name . '$');
        }

        if ($show_product) {
            $category->with('products');
        }

        if ($stores_id) {
            $category->where('stores_id', $stores_id);
        }

        return ResponseFormatter::success(
            [
                'category' => $category->paginate($limit)
            ],
            'Data Kategori Berhasil Diambil'
        );
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'stores_id' => 'required'
            ]);

            $category = ProductCategory::create([
                'name' => $request->name,
                'stores_id' => $request->stores_id,
            ]);

            return ResponseFormatter::success(
                $category,
                'Berhasil Menambah Kategory',
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                $error,
                'Gagal Menambah Kategory',
                404
            );
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stores_id' => 'required'
        ]);
        
        $category = ProductCategory::find($request->id);

        $category->name = $request->name;
        $category->stores_id = $request->stores_id;

        $category->save();

        return ResponseFormatter::success(
            $category,
            'Berhasil Mengubah Kategory',
        );

    }

    public function delete($id)
    {
        $category = ProductCategory::find($id);
 
        $category->delete();

        return ResponseFormatter::success(
            $category,
            'Berhasil Menghapus Kategory',
        );
    }
}
