<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function getData()
    {
        $data = Category::with('suppliers')->get();
        return $this->success('', $data);
    }

    public function getItem()
    {
        $this->validateId();
        $category = Category::with('suppliers')->findOrFail($this->model_id);
        return $this->success('', $category);
    }
}
