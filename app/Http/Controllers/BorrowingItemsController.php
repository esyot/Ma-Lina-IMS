<?php

namespace App\Http\Controllers;

use App\Models\BorrowedItem;
use App\Models\BorrowingSlip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingItemsController extends Controller
{
    public function index()
    {
        return inertia('BorrowingItems', [
            'user' => Auth::user(),
            'borrowing_slips' => BorrowingSlip::with('borrowedItems.item')
                ->get(),

        ]);

    }

    public function submit(Request $request)
    {

        $slip = BorrowingSlip::create([
            'name' => $request->input('name'),
            'contact_no' => $request->input('contact_no'),
            'purpose' => $request->input('purpose'),
            'status' => 'ongoing',
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
        ]);

        $items = $request->selected_items;

        foreach ($items as $item)
        {
            BorrowedItem::create([
                'borrowing_slip_id' => $slip->id,
                'item_id' => $item['id'],
                'quantity' => $item['qty'],
            ]);
        }

        if ($items)
        {
            return redirect()->back()->with('success', 'Borrowing slip created successfully!');
        } else
        {
            return redirect()->back()->withErrors('error', 'Failed to create borrowing slip. Please try again.');
        }
    }

    public function markAsReturned(Request $request)
    {

        $slip = BorrowingSlip::find($request->input('id'));

        if ($slip)
        {
            $slip->status = 'returned';
            $slip->save();

            return redirect()->back()->with('success', 'Borrowing slip marked as returned successfully!');
        } else
        {
            return redirect()->back()->withErrors('error', 'Failed to mark borrowing slip as returned. Please try again.');
        }
    }

    public function filterByStatus($status)
    {
        if ($status === 'all')
        {
            $borrowing_slips = BorrowingSlip::with('borrowedItems.item')->get();
        } else
        {
            $borrowing_slips = BorrowingSlip::where('status', $status)
                ->with('borrowedItems.item')
                ->get();
        }

        return inertia('BorrowingItems', [
            'user' => Auth::user(),
            'status' => $status,
            'borrowing_slips' => $borrowing_slips,
        ]);
    }

}
