<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavouriteController extends Controller
{

    function addFav(Request $request)
    {
        $code = $request->code;
        $mobile = $request->mobile;
        $ProductDetails = DB::table('product_list')->Where('product_code', $code)->get();

        foreach ($ProductDetails as $ProductDetail) {
            $result = Favourite::insert([
                'title' => $ProductDetail->title,
                'image' => $ProductDetail->image,
                'product_code' => $code,
                'mobile' => $mobile,
            ]);
            return $result;
        }
    }


    public function favList($mobile)
    {
        $result = Favourite::where('mobile', $mobile)->orderBy('id', 'desc')->get();
        return response()->json($result);
    }
}
