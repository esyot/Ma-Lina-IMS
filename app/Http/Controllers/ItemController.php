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

        $request->validate([
            'search_value' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        $searchTerm = $request->input('search_value');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Get the latest stock per item
        $latestStocks = Stock::with([
            'item' => function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%$searchTerm%")
                        ->orWhere('description', 'LIKE', "%$searchTerm%");
                })->select('id', 'name', 'img', 'category_id', 'UOM')
                    ->with('category:id,name');
            }
        ])
            ->get()
            ->groupBy('item_id')
            ->map(function ($group) {
                return $group->sortByDesc('date')->first();
            })
            ->filter(fn($stock) => $stock->item !== null)
            ->map(function ($stock) {
                $stock->item->final_inv = $stock->final_inv ?? 0;
                return $stock->item;
            })
            ->unique('id')
            ->values();

        // Get items with no stock
        $stockedItemIds = $latestStocks->pluck('id');

        $itemsWithoutStock = Item::where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%$searchTerm%")
                ->orWhere('description', 'LIKE', "%$searchTerm%");
        })
            ->whereNotIn('id', $stockedItemIds)
            ->with('category:id,name')
            ->get()
            ->map(function ($item) {
                $item->final_inv = 0;
                $item->borrowed = 0;
                return $item;
            });

        // Merge all items
        $items = $latestStocks->merge($itemsWithoutStock);

        $itemIds = $items->pluck('id');

        // Get borrowed items within the date range
        $borrowedItems = BorrowedItem::whereIn('item_id', $itemIds)
            ->whereHas('borrowingSlip', function ($query) use ($dateStart, $dateEnd) {
                $query->where('status', 'ongoing')
                    ->whereBetween('created_at', [$dateStart, $dateEnd]);
            })
            ->get()
            ->groupBy('item_id')
            ->map(function ($group) {
                return $group->sum('quantity'); // Total quantity per item
            });

        // Subtract borrowed quantities from available inventory
        $items = $items->map(function ($item) use ($borrowedItems) {
            $borrowedQty = $borrowedItems->get($item->id) ?? 0;
            $item->borrowed = $borrowedQty;
            $item->final_inv = max(0, $item->final_inv - $borrowedQty);
            return $item;
        })
            ->filter(function ($item) {
                return isset($item->category) && $item->category->name === 'Equipments';
            })
            ->values();

        return response()->json($items);


    }

}
