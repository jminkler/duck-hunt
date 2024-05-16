<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Equipment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'equipment';
    protected $fillable = ['name', 'type', 'value'];
}
