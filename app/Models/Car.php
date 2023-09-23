<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Car
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

    /**
     * @param string $vin
     */
    public function setVin(string $vin) : void {
        $this->attributes['vin'] = $vin;
    }

    /**
     * @param string $mark
     */
    public function setMark(string $mark) : void {
        $this->attributes['mark'] = $mark;
    }

}
