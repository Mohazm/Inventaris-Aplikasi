<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Loans_items(){
        return $this->hasMany(Loans_item::class);
    }
   // In Borrower model:
   public function student()
{
    return $this->hasOne(Student::class, 'id', 'borrower_id');
}

public function teacher()
{
    return $this->hasOne(Teacher::class, 'id', 'borrower_id');
}


}
