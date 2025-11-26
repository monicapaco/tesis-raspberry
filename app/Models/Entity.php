<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

    protected $fillable =[
        'type',
        'name',
        'type_document',
        'n_document',
        'address',
        'region',
        'province',
        'district',
        'phone',
        'email'];
    public function income():HasMany{
        return $this->hasMany(Income::class);
    }
    public function sale():HasMany{
        return $this->hasMany(Sale::class);
    }
}
