<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    public function show($category)
    {
        $category = Category::find($category);

        if( $category )
        {
            return response()->json($category);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if( $validator->fails() ){
            return response()->json([
                'message' => 'validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category_deleted = $category->name;

        $category->delete();

        return response()->json([
            'message' => "Category \"$category_deleted\" deleted successfully",
        ], 200);
    }
}
