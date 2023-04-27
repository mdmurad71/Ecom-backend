<?php

namespace App\Http\Controllers;

use App\Models\ProductCarts;
use App\Models\ProductListModel;
use App\Models\ProductOrderModel;
use GrahamCampbell\ResultType\Success;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCartController extends Controller
{

    function addToCart(Request $request)
    {

        $color = $request->input('color');
        $size = $request->input('size');
        $quantity = $request->input('quantity');
        $mobileNo = $request->input('mobileNo');
        $product_code = $request->input('product_code');

        $datas = DB::table('product_list')->Where('product_code', '=', $product_code)->get();

        foreach ($datas as $data) {
            $total = $data->price * $quantity;
            $result = DB::table('product_cart')->Where('product_code', $data->product_code)->insert([
                'img' => $data->image,
                'product_name' => $data->title,
                'product_code' => $data->product_code,
                'shop_name' => $data->shop_name,
                'shop_code' => $data->shop,
                'product_info' => "Color: " . $color . "Size: " . $size,
                'product_quantity' => $quantity,
                'mobile' => $mobileNo,
                'unit_price' => $data->price,
                'total_price' => $total,

            ]);
            return $result;
        }
    }







    public function CartCount($mobile)
    {
        $cartCount = ProductCarts::where('mobile', $mobile)->count();
        return response()->json($cartCount);
    }

    public function CartDetails($mobile)
    {
        $result = ProductCarts::where('mobile', $mobile)->orderBy('id', 'desc')->get();
        return response()->json($result);
    }

    public function CartItemPlus($id, $quantity, $price)
    {
        $newQuantity = $quantity + 1;
        $total_price = $newQuantity * $price;
        $result = ProductCarts::where('id', $id)->update([
            'product_quantity' => $newQuantity,
            'total_price' => $total_price
        ]);

        return $result;
    }

    public function CartItemMinus($id, $quantity, $price)
    {
        $newQuantity = $quantity - 1;
        $total_price = $newQuantity * $price;
        $result = ProductCarts::where('id', $id)->update([
            'product_quantity' => $newQuantity,
            'total_price' => $total_price
        ]);

        return response()->json($result);
    }

    public function RemoveCartList($id)
    {
        $itemDelete = ProductCarts::where('id', $id)->delete();
        return $itemDelete;
    }



    public function PostOrder(Request $request)
    {

        $city = $request->input('city');
        $paymentMethod = $request->input('payment_method');
        $name = $request->input('name');
        $address = $request->input('delivery_address');
        $mobileNo = $request->input('mobile');
        $invoice = $request->input('invoice_no');
        $delivery_charge = $request->input('delivery_charge');

        date_default_timezone_set('Asia/Dhaka');
        $request_time = date('h:i:sa');
        $request_date = date('d-m-y');

        // $cartList = ProductCarts::where('mobile', $mobileNo)->get();
        $datas = DB::table('product_cart')->Where('mobile', '=', $mobileNo)->get();

        $cartDataDelete = "";

        foreach ($datas as $cartItem) {
            $data =  [
                'invoice_no' => "C" . $invoice,
                'product_name' => $cartItem->product_name,
                'product_code' => $cartItem->product_code,
                'shop_name' => $cartItem->shop_name,
                'shop_code' => $cartItem->shop_code,
                'product_info' => $cartItem->product_info,
                'product_quantity' => $cartItem->product_quantity,
                'unit_price' => $cartItem->unit_price,
                'total_price' => $cartItem->total_price,
                'mobile' => $cartItem->mobile,
                'name' => $name,
                'payment_method' => $paymentMethod,
                'delivery_address' => $address,
                'city' => $city,
                'delivery_charge' => $delivery_charge,
                'order_date' => $request_date,
                'order_time' => $request_time,
                'order_status' => 'pending'
            ];

            $result = DB::table('product_order')->insert($data);

            if ($result) {
                $cartItemDelete = ProductCarts::where('id', $cartItem->id)->delete();
                if ($cartItemDelete) {
                    return $cartDataDelete = 1;
                } else {
                    return $cartDataDelete = 0;
                }
            }
            // return $result;
        }


        return response()->json($cartDataDelete);
    }
}



// if ($result) {
//     $cartItemDelete = ProductCarts::where('id', $cartItem['id'])->delete();
//     if ($cartItemDelete) {
//         return $cartDataDelete = "1";
//     } else {
//         return $cartDataDelete = "0";
//     }
// }