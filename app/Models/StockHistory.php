<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'user_id',
        'type',
        'quantity',
        'stock_after',
        'note',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
