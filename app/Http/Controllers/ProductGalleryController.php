<?php

namespace App\Http\Controllers;

use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductGalleryController extends Controller
{
    public function upload(Request $request)
    {
       
        if ($request->hasFile('image1')) {
            $image = new ProductGallery();
            $image->products_id = $request->id;
            $path = $request->file('image1')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image2')) {
            $image = new ProductGallery();
            $image->products_id = $request->id;
            $path = $request->file('image2')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image3')) {
            $image = new ProductGallery();
            $image->products_id = $request->id;
            $path = $request->file('image3')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image4')) {
            $image = new ProductGallery();
            $image->products_id = $request->id;
            $path = $request->file('image4')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image5')) {
            $image = new ProductGallery();
            $image->products_id = $request->id;
            $path = $request->file('image5')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }

        if ($request->hasFile('image6')) {
            $image = new ProductGallery();
            $image->products_id = $request->id;
            $path = $request->file('image6')->store('productGalleries');
            $image->url = $path;
            $image->save();
        }



        // if ($request->galleries != null) {
            // foreach ($request->images as $item) {
            //     $image = new ProductGallery();
            //     $image->products_id = $request->id;

            //     // $imageName = time().'.'.$item['url']->extension(); 
            //     $imageName = Str::random(20).time().'.'.$item['url']->extension(); 
                
            //     $path = $item['url']->storeAs('productGalleries', $imageName);
            //     $image->url = $path;

            //     // if ($item->hasFile('url')) {
            //     //     $path = $item->file('url')->store('productGalleries');
            //     //     $image->url = $path;
            //     // }
            //     $image->save();
            // }  
        // $image->save();

        return ["result" => $image];
    }
}
