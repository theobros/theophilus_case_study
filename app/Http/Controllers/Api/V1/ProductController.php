<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Responses\ErrorMessage;
use App\Http\Responses\SuccessMessage;
use App\Models\Products;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $list = Products::orderBy('id', 'desc')->with('category')->paginate(10);

        if ($list->count() > 0) {
            return response()->json($list, 200);
        }
        return new ErrorMessage("Products not found.", 404);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddProductRequest $request)
    {
        $data = $request->validated();
        //check if image is available
        if ($request->file('avatar')) {
            $folder = 'public/uploads/avatar';
            $image_name = time() . '-' . $data['name'];
            $filePath = $request->file('avatar')->storeAs($folder, $image_name);
            $data['avatar'] = $filePath;
        }
        Products::create($data);
        return new SuccessMessage('Product added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $record = Products::with('category')->findOrFail($id);
            return response()->json($record, 200);
        } catch (\Exception $exception) {
            return new ErrorMessage('Product not found.', 404);
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
            Products::findOrFail($id)->destroy($id);
            return new SuccessMessage('Product deleted successfully');
        } catch (\Exception $exception) {
            return new ErrorMessage('Product not found', 404);
        }
    }
}
