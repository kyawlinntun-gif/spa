<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();
        return $product;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric'
        ],[
            'name.required' => 'အမည် ထည့်ရန် လိုအပ်သည်။',
            'name.string' => 'အမည်သည် စာသား ဖြစ်ရမည်။',
            'price.required' => 'ပိုက်ဆံ ထည့်ရန် လိုအပ်သည်။',
            'price.numeric' => 'ပိုက်ဆံသည် ဂဏန်း ဖြစ်ရမည်။'
        ]);
        // Store the product
        $product = Product::create(['name' => $request->name, 'price' => $request->price]);

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Show the product
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // Validate the request
        $request->validate([
            'name' => 'nullable|string',
            'price' => 'nullable|numeric'
        ],[
            'name.string' => 'အမည်သည် စာသား ဖြစ်ရမည်။',
            'price.numeric' => 'ပိုက်ဆံသည် ဂဏန်း ဖြစ်ရမည်။'
        ]);
        // Update the product
        $product->update(['name' => $request->name, 'price' => $request->price]);
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Delete the product
        return $product->delete();
    }
}
