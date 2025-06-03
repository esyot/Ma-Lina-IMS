<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {


        return inertia('Inventory', [
            'categories' => Category::all(),
            'uoms' => config('page.uoms'),
            'items' => Stock::with([
                'item' => function ($query) {
                    $query->select('id', 'name', 'img', 'category_id', 'UOM')
                        ->with('category:id,name');
                }
            ])
                ->get()
                ->groupBy('item_id')
                ->map(function ($group) {
                    return $group->sortByDesc('date')->first();
                })
                ->values()
                ->map(function ($stock) {
                    if ($stock->item)
                    {
                        $stock->item->final_inv = $stock->final_inv;
                    }
                    return $stock->item;
                })
                ->filter()
                ->unique('id')
                ->values()
            ,

            'success' => session('success') ?? null,
        ]);

    }
}
