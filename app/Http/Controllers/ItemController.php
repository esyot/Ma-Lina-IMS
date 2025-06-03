<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdditemRequest;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store(AdditemRequest $request)
    {

        $itemData = $request->only(['name', 'description', 'category_id', 'UOM']);

        if ($request->hasFile('img'))
        {

            $itemData['img'] = $request->file('img')->store('items', 'public');
        }

        $item = Item::create($itemData);

        if ($item)
        {
            return redirect()->back()->with('success', 'Item created successfully!');
        } else
        {
            return redirect()->back()->withErrors('error', 'Failed to create item. Please try again.');

        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search_value');

        $items = Stock::with([
            'item' => function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                    ->select('id', 'name', 'img', 'category_id', 'UOM')
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
            ->values();

        return response()->json($items ?? []);
    }

}
