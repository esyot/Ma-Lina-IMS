<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdditemRequest;
use App\Models\BorrowedItem;
use App\Models\BorrowingSlip;
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

        $ongoingSlips = BorrowingSlip::where('status', 'ongoing')
            ->pluck('id')
            ->toArray();

        $borrowedItems = BorrowedItem::whereIn('item_id', $items->pluck('id'))
            ->whereIn('borrowing_slip_id', $ongoingSlips)
            ->get()
            ->groupBy('item_id')
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first();
            });

        $items = $items->map(function ($item) use ($borrowedItems) {
            $borrowedQty = $borrowedItems->has($item->id) ? $borrowedItems->get($item->id)->quantity : 0;
            $item->borrowed = $borrowedQty;

            $item->final_inv = max(0, $item->final_inv - $borrowedQty);
            return $item;
        });

        $items = $items->filter(function ($item) {
            return $item->final_inv > 0 || $item->borrowed > 0;
        });

        return response()->json($items ?? []);
    }

}
