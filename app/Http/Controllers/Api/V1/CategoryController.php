<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $list = Category::orderBy('id', 'desc')->get();

        if ($list->count() > 0) {
            return response()->json($list, 200);
        }
        return new ErrorMessage("Category not found.", 404);
    }
}
