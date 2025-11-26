<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Income extends Model
{
    use HasFactory;
    protected $fillable =[
        'provider_id',
        'type_voucher',
        'serial_voucher',
        'number_voucher',
        'status',
        'total'
        ];
    public function detailIncome():HasMany
    {
        return $this->hasMany(DetailIncome::class);
    }
    public function provider(): BelongsTo{
        return $this->belongsTo(Entity::class,'provider_id');
    }
}
