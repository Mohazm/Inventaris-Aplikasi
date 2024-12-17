<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    protected $fillable = ['name', 'email', 'phone'];

    public function borrower()
    {
        return $this->hasOne(Borrower::class,'borrower_id');
    }
    
}
