<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;
    protected $fillable =[
        'client_id',
        'carrier_id',
        'type_voucher',
        'serial_voucher',
        'number_voucher',
        'status',
        'total'
        ];
    public function detailSale():HasMany{
        return $this->hasMany(DetailSale::class);
    }
    public function client():BelongsTo{
        return $this->belongsTo(Entity::class,'client_id');
    }
    public function carrier():BelongsTo{
        return $this->belongsTo(Entity::class,'carrier_id');
    }
}
