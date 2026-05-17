<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'fecha',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }
}
