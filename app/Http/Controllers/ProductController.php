<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Courier;
use App\Models\Couriers;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $users_id = $request->input('users_id');
        $limit = $request->input('limit');
        // $name = $request->input('name');
        $name = $request->name;
        $deskripsi = $request->input('deskripsi');
        $price = $request->input('price');
        $stok = $request->input('stok');
        $berat = $request->input('berat');
        $panjang = $request->input('panjang');
        $lebar = $request->input('lebar');
        $tinggi = $request->input('tinggi');
        $status = $request->input('status');
        $categories = $request->input('categories');
        $address = $request->input('address');
        $jasa_antar = $request->input('jasa_antar');
        $rating = $request->input('rating');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');
        
        if ($id) {
            $product = Product::with(['category', 'galleries', 'variations', 'reviews'])->find($id);

            if ($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data produk berhasil diambil',
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ada',
                    404,
                );
            }
        }

        $product = Product::with(['category', 'galleries', 'variations', 'reviews'])->where('users_id', $users_id);

        if ($name) {
            $product->where('products.name', 'LIKE', '$' . $name . '$');
        }

        if ($deskripsi) {
            $product->where('deskripsi', 'like', '$' . $deskripsi . '$');
        }

        if ($price_from) {
            $product->where('price', '>=', $price_from);
        }

        if ($price_to) {
            $product->where('price', '>=', $price_to);
        }

        if ($categories) {
            $product->where('categories', $categories);
        }

        if ($status) {
            $product->where('status', $status);
        }

        if ($address) {
            $product->where('address', $address);
        }

        if ($jasa_antar) {
            $product->where('jasa_antar', $jasa_antar);
        }
        
        if ($rating) {
            $product->where('star', $rating);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Produk Berhasil Diambil'
        );
    }
    
    public function update(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $product = Product::where('id', $id)->first();

        $product->status = $status;

        return ResponseFormatter::success(        
            $product->load('variations', 'galleries')
            , 'Produk berhasil ditambah'
        );
    }
    
    public function updateAll(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $product = Product::where('id', $id)->first();

        $users_id = $request->users_id;
        $name = $request->name;
        $categories_id = $request->categories_id;
        $deskripsi = $request->deskripsi;
        $price = $request->price;
        $stok = $request->stok;
        $berat = $request->berat;
        $panjang = $request->panjang;
        $lebar = $request->lebar;
        $tinggi = $request->tinggi;
        $status = $request->status;
        $category = $request->category;
        $couriers_id = 8  ;

        if ($name != $product->name)  {
            $product->status = "POST";
        }

        
        if ($request->hasFile('image1')) {
            $image = new ProductGallery();
            $image->products_id = $product->id;
            $path = $request->file('image1')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image2')) {
            $image = new ProductGallery();
            $image->products_id = $product->id;
            $path = $request->file('image2')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image3')) {
            $image = new ProductGallery();
            $image->products_id = $product->id;
            $path = $request->file('image3')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image4')) {
            $image = new ProductGallery();
            $image->products_id = $product->id;
            $path = $request->file('image4')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image5')) {
            $image = new ProductGallery();
            $image->products_id = $product->id;
            $path = $request->file('image5')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image6')) {
            $image = new ProductGallery();
            $image->products_id = $product->id;
            $path = $request->file('image6')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }


        return ResponseFormatter::success(        
            $product->load('variations', 'galleries')
            , 'Produk berhasil ditambah'
        );
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'users_id' => 'required',
                'name' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'price' => 'required',
                'stok' => 'required',
                'status' => 'string',
                'category' => 'required',
    
                'galleries' => 'array',
    
                'variations' => 'array',
    
                'berat' => 'required',
                'panjang' => 'required',
                'lebar' => 'required',
                'tinggi' => 'required',
            ]);
    
            // $courier = Courier::create([
            //     'jne_kilat' => $request->jne_kilat,
            //     'sicepat_kilat' => $request->sicepat_kilat,
            //     'jnt_kilat' => $request->jnt_kilat,
            //     'jne_reguler' => $request->jne_reguler,
            //     'sicepat_reguler' => $request->sicepat_reguler,
            //     'jnt_reguler' => $request->jnt_reguler,
            //     'jne_ekonomis' => $request->jne_ekonomis,
            //     'sicepat_ekonomis' => $request->sicepat_ekonomis,
            //     'jnt_ekonomis' => $request->jnt_ekonomis,
            //     'jne_kargo' => $request->jne_kargo,
            //     'sicepat_kargo' => $request->sicepat_kargo,
            //     'jnt_kargo' => $request->jnt_kargo,
            // ]);
    
            $product = Product::create([
                'users_id' => $request->users_id,
                'name' => $request->name,
                'categories_id' => $request->categories_id,
                'deskripsi' => $request->deskripsi,
                'price' => $request->price,
                'stok' => $request->stok,
                'berat' => $request->berat,
                'panjang' => $request->panjang,
                'lebar' => $request->lebar,
                'tinggi' => $request->tinggi,
                'status' => $request->status,
                'category' => $request->category,
                'couriers_id' => 8  ,
            ]);

            // $request->validate([
            //     'url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // ]);

            if ($request->hasFile('image1')) {
                $image = new ProductGallery();
                $image->products_id = $product->id;
                $path = $request->file('image1')->store('productGalleries');
                $image->url = $path;
                $image->save();
            }

            if ($request->hasFile('image2')) {
                $image = new ProductGallery();
                $image->products_id = $product->id;
                $path = $request->file('image2')->store('productGalleries');
                $image->url = $path;
                $image->save();
            }

            if ($request->hasFile('image3')) {
                $image = new ProductGallery();
                $image->products_id = $product->id;
                $path = $request->file('image3')->store('productGalleries');
                $image->url = $path;
                $image->save();
            }

            if ($request->hasFile('image4')) {
                $image = new ProductGallery();
                $image->products_id = $product->id;
                $path = $request->file('image4')->store('productGalleries');
                $image->url = $path;
                $image->save();
            }

            if ($request->hasFile('image5')) {
                $image = new ProductGallery();
                $image->products_id = $product->id;
                $path = $request->file('image5')->store('productGalleries');
                $image->url = $path;
                $image->save();
            }

            if ($request->hasFile('image6')) {
                $image = new ProductGallery();
                $image->products_id = $product->id;
                $path = $request->file('image6')->store('productGalleries');
                $image->url = $path;
                $image->save();
            }

            // if ($request->galleries != null) {
            //     foreach ($request->galleries as $item) {
            //         $image = new ProductGallery();
            //         $image->products_id = $product->id;
    
            //         // $imageName = time().'.'.$item['url']->extension(); 
            //         $imageName = Str::random(20).time().'.'.$item['url']->extension(); 
                    
            //         $path = $item['url']->storeAs('productGalleries', $imageName);
            //         $image->url = $path;
    
            //         // if ($item->hasFile('url')) {
            //         //     $path = $item->file('url')->store('productGalleries');
            //         //     $image->url = $path;
            //         // }
            //         $image->save();
            //     }  
            // }          
    
            if ($request->variations != null) {
                foreach ($request->variations as $item) {
                    ProductVariation::create([
                        'products_id' => $product->id,
                        'name' => $item['name'],
                        'detail' => $item['detail'],
                        'price' => $item['price'],
                    ]);
                }
            }       
           
    
            
            return ResponseFormatter::success(
                
                    // 'product' => $product,
                $product->load('variations', 'galleries')
                    // 'product' => $product->load('variations', 'galleries')
                ,
                'Produk berhasil ditambah'
            );
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                [
                    "message" => "Something went wrong",
                    "errors" => $th
                ],
                "Produk Gagal ditambah", 404
            );
        };
    }

    
    public function delete($id)
    {
        $product = Product::find($id);
 
        $product->delete();

        return ResponseFormatter::success(
            $product,
            'Berhasil Menghapus Product',
        );
    }
    
}
