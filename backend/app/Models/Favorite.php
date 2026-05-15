<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_token',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
