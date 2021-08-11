<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\ProductCategory;



class ProductCtegoryController extends Controller
{
    public function all(Request $request){
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('id');
        $show_product = $request->input('show_product');
    }

    if ($id) {
            $category = ProductCategory::with(['category'])->find($id);
            if ($category) {
                return ResponseFormatter::success($product, 'Data category berhasil di ambil');
            } else {
                return ResponseFormatter::error(null, 'Data category tidak tersedia', 404);
            }
            
        }

    $category = ProductCategory::query();

    if ($name) {
        $category->where('name', 'like', '%', $name, '%');
    }
    if ($show_product) {
        $category->with('products');
    }


    return ResponseFormatter::success($product->paginate($limit), 'Data category berhasil di ambil');

}