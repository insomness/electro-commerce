<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = \Cart::getContent();
        return $this->loadTheme('carts.index', compact('carts'));
    }

    public function add(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::find($request->productId);

            // add the product to cart
            \Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => [],
                'associatedModel' => $product,
            ]);

            return $this->_ajaxResponse();
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $productId = $request->productId;
            $quantityChanged = $request->quantityChanged;

            \Cart::update($productId, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantityChanged
                ]
            ]);

            $cart = \Cart::get($productId);
            return response()->json(['cart' => $cart]);
        }
    }

    public function destroy(Request $request, $id = null)
    {
        if ($request->ajax()) {
            \Cart::remove($request->productId);
            return $this->_ajaxResponse();
        }
        \Cart::remove($id);
        return back();
    }

    public function clear()
    {
        \Cart::clear();
        return redirect()->back()->with('status', 'Product has been deleted!');
    }

    private function _ajaxResponse()
    {
        $carts = \Cart::getContent();
        $result = [];
        foreach ($carts as $cart) {
            $result[] = [
                'id' => $cart->id,
                'image' => $cart->associatedModel->productImages->first()->path,
                'name' => $cart->name,
                'price' => $cart->price,
                'quantity' => $cart->quantity,
                'associatedModel' => $cart->associatedModel,
            ];
        }

        $subTotal = \Cart::getSubTotal();
        return response()->json(['result' => $result, 'subTotal' => $subTotal]);
    }
}
