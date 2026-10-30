<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset_type',
        'serial_number',
        'model',
        'manufacturer',
        'purchase_date',
        'condition',
        'status',
        'assigned_to',
        'department',
        'location',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];
}
