<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Duck extends Model
{
    # use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'ducks';
    protected $fillable = ['name', 'speed', 'armor', 'evasiveness', 'health', 'equipment'];
}
