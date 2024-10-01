<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenEnergyGallery extends Model
{
    use HasFactory;
    protected $table = 'green_energy_galleries';
    protected $fillable = ['heading', 'image'];

}
