<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function transactionsIn(): HasMany
    {
        return $this->hasMany(TransactionIn::class, 'item_id');
    }

    public function transactionsOut(): HasMany
    {
        return $this->hasMany(TransactionOut::class, 'item_id');
    }

    public function itemLoans(): HasMany
    {
        return $this->hasMany(ItemLoan::class, 'item_id');
    }
}
