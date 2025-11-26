<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSale extends Model
{
    use HasFactory;
    protected $fillable =[
        'sale_id',
        'item_id',
        'quantity',
        'sale_price',
        'purchase_price',
        //'discount'
        ];
    public function sale() :BelongsTo{
        return $this->belongsTo(Sale::class,'sale_id');
    }
    public function item(): BelongsTo{
        return $this->belongsTo(Item::class,'item_id');
    }
}
