<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {

        return inertia('Inventory', [
            'user' => Auth::user(),
            'categories' => Category::all(),
            'uoms' => config('page.uoms'),
            'items' => Item::with(['category:id,name'])
                ->select('id', 'name', 'img', 'category_id', 'UOM')
                ->get()
                ->map(function ($item) {

                    $latestStock = Stock::where('item_id', $item->id)
                        ->orderByDesc('date')
                        ->first();

                    $item->final_inv = $latestStock ? $latestStock->final_inv : 0;
                    return $item;
                })

            ,

            'success' => session('success') ?? null,
        ]);

    }
}
