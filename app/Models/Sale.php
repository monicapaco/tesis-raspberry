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
        'payment_type',
        'payment_status',
        'paid_at',
        'total'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
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

    public function getFechaPagoFormateadaAttribute()
    {
        return $this->paid_at
            ? $this->paid_at->format('d/m/Y H:i')
            : null;
    }

}
