<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [
        "id",
    ];

    public function transactionProduct(){
        return $this->belongsTo(TransactionProduct::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
