<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index($categoryId = null, $searchTerm = null)
    {


        return inertia('Inventory', [
            'user' => Auth::user(),
            'searchTerm' => $searchTerm,
            'categoryId' => $categoryId,
            'categories' => Category::all(),
            'uoms' => config('page.uoms'),
            'items' => Item::when($searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            })
                ->when($categoryId && $categoryId !== 'all', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                })
                ->with('category:id,name')
                ->select('id', 'name', 'description', 'img', 'category_id', 'UOM')
                ->paginate(20)
                ->through(function ($item) {
                    $item->final_inv = Stock::where('item_id', $item->id)
                        ->latest('date')
                        ->value('final_inv') ?? 0;
                    return $item;
                }),
            'success' => session('success') ?? null,
        ]);

    }
}
