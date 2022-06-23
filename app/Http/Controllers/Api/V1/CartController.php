<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartRequest;
use App\Http\Requests\EditCartRequest;
use App\Http\Responses\ErrorMessage;
use App\Http\Responses\SuccessMessage;
use App\Models\Cart;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:carts,session_id'
        ]);


        $list = Cart::where('session_id', $request->session_id)->with(['cart_items', 'cart_items.products'])->get();

        if ($list->count() > 0) {
            return response()->json($list, 200);
        }
        return new ErrorMessage("Cart  not found.", 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCartRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = NULL;
        if (Auth::guard('api')->check()) {
            $data['user_id'] = Auth::guard('api')->user()->id;
        }

        $cart =  Cart::create([
            'session_id'    => $data['session_id'],
            'user_id'       => $data['user_id'],
        ]);

        $cart->cart_items()->createMany(
            $data['products']
        );

        return new SuccessMessage('Product added successfully in cart.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditCartRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = NULL;
            if (Auth::guard('api')->check()) {
                $data['user_id'] = Auth::guard('api')->user()->id;
            }

            $cart = Cart::where('session_id', $id)->with('cart_items')->firstOrFail();
            $cart->user_id = $data['user_id'];

            $request_product = array_column($data['products'], 'product_id');
            $cart_product = array_column($cart->cart_items->toArray(), 'product_id');
            //get id to delete which is not in request but present in cart
            $diff_array = array_diff($cart_product, $request_product);

            foreach ($diff_array as $key => $value) {
                $cart_item = $cart->cart_items()->where('product_id', $value)->first();
                $cart_item->delete();
            }

            foreach ($data['products'] as $key => $product) {
                CartItems::updateOrCreate(
                    [
                        'cart_id' => $cart->id,
                        'product_id' => $product['product_id']
                    ],
                    [
                        'cart_id' => $cart->id,
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],

                    ]
                );
            }
            return new SuccessMessage('Cart updated successfully');
        } catch (\Exception $exception) {
            return new ErrorMessage('Session id not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $cart = Cart::where('session_id', $id)->firstOrFail();
            $cart->cart_items()->delete();
            $cart->delete();
            return new SuccessMessage('Cart deleted successfully');
        } catch (\Exception $exception) {
            return new ErrorMessage('Cart not found', 404);
        }
    }
}
