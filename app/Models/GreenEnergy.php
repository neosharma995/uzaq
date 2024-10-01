<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenEnergy extends Model
{
    use HasFactory;
    protected $table = 'green_energies';
    protected $fillable = [
        'name', 'image', 'short_description', 'long_description',
    ];
}
