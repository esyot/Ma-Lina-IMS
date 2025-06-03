<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        return inertia(
            'Stocks',
            [
                'user' => Auth::user(),
                'stocks' => Stock::orderBy('date', 'DESC')
                    ->get()
                    ->groupBy('date')
                    ->map(function ($stocks) {
                        return $stocks->values();
                    })
                ,

            ]
        );
    }

    public function addStockRecord()
    {

        return inertia(
            'AddStockRecord',
            [
                'user' => Auth::user(),
                'items' => Item::all(),
            ]
        );
    }


    public function store(Request $request)
    {


        $date = $request->input('date');
        $stocks = $request->input('stocks');

        foreach ($stocks as $stock)
        {
            Stock::create([
                'item_id' => $stock['item_id'],
                'beg_inv' => $stock['beg_inv'],
                'stock_in' => $stock['stock_in'],
                'stock_out' => $stock['stock_out'],
                'final_inv' => $stock['beg_inv'] + $stock['stock_in'] - $stock['stock_out'],
                'date' => $date,
            ]);
        }

        return redirect()->route('stocks')
            ->with('success', 'Stock records added successfully.');

    }

    public function stockRecord($date)
    {
        $stocks = Stock::where('date', $date)
            ->orderBy('item_id')
            ->get();

        $items = Item::all()->keyBy('id');

        $stocks = $stocks->map(function ($stock) use ($items) {
            return [
                'item' => $items->get($stock->item_id),
                'beg_inv' => $stock->beg_inv,
                'stock_in' => $stock->stock_in,
                'stock_out' => $stock->stock_out,
                'final_inv' => $stock->final_inv,
            ];
        });
        $stocks = $stocks->values();
        $date = date('F j, Y', strtotime($date));


        return inertia(
            'StockRecord',
            [
                'stocks' => $stocks,
                'date' => $date,
            ]
        );
    }
}
