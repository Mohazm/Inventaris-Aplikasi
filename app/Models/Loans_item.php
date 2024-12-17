<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Loans_item extends Model
{
    use HasFactory,Notifiable;

    protected $guarded = [];
    public function validateStatusChange($newStatus)
    {
        $validStatuses = ['dipakai', 'di kembalikan']; // Status yang valid
        return in_array($newStatus, $validStatuses);
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function tendik()
    {
        return $this->belongsTo(Tendik::class, 'tendik_id');
    }

    public function itemReturn()
    {
        return $this->hasOne(Returns_item::class, 'peminjaman_id');
    }
    
}
