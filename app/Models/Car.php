<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Car
 *
 * @mixin Eloquent
 */
class Car extends Model
{
    use HasFactory;

    public const VIM_FIELD_VALUE_LENGTH = 17;

    /**
     * @var array<int,string>
     */
    protected $fillable = [
        'vin','mark'
    ];

}
