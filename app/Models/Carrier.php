<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrier extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'type_document',
        'n_document',
        ];
    public function income():HasMany{
        return $this->hasMany(Sale::class);
    }
}
