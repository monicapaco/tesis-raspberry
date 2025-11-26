<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;
    /*
    * un item tiene una categoria
    */
    protected $fillable =[
        'category_id',
        'codevar',
        'name',
        'stock',
        'description',
        'img',
        'condition',
        'status'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function detailIncome(): HasMany{
        return $this->hasMany(DetailIncome::class);
    }
}
