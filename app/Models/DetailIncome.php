<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailIncome extends Model
{
    use HasFactory;
    protected $fillable =[
        'income_id',
        'item_id',
        'quantity',
        'sale_price',
        'purchase_price'
        ];
    public function income() : BelongsTo{
        return $this->belongsTo(Income::class,'income_id');
    }
    public function item(): BelongsTo{
        return $this->belongsTo(Item::class,'item_id');
    }
}
