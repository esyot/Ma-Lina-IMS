<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedItem extends Model
{
    protected $guarded = [];

    public function borrowingSlip()
    {
        return $this->belongsTo(BorrowingSlip::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
