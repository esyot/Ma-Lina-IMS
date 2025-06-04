<?php

namespace App\Http\Controllers;

use App\Models\BorrowedItem;
use App\Models\BorrowingSlip;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return inertia('Home', [
            'user' => Auth::user(),
            'missing_items' => (
            $missingItems = BorrowedItem::whereIn(
                    'borrowing_slip_id',
                    BorrowingSlip::where('date_end', '<', now())
                        ->where('status', 'ongoing')
                        ->pluck('id')
                )
                    ->with([
                        'item.category',
                        'borrowingSlip',
                    ])
                    ->get()
            )->isEmpty() ? null : $missingItems
            ,

            'out_of_stocks' => (
            $outOfStocks = Stock::where('final_inv', '<', 5)->with([
                    'item' => function ($query) {
                        $query->select('id', 'name', 'img', 'category_id', 'UOM')->with('category:id,name');
                    }
                ])->get()->groupBy('item_id')->map(function ($group) {
                    return $group->sortByDesc('date')->first();
                })->values()->map(function ($stock) {
                    if ($stock->item) {
                        $stock->item->final_inv = $stock->final_inv;
                    }
                    return $stock->item;
                })->filter()->unique('id')->values()
            )->isEmpty() ? null : $outOfStocks,

        ]);
    }
}
