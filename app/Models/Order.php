<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'paid_at', 'completed_at'];
    public $timestamps = true;

    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
