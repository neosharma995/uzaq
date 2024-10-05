<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryNew extends Model
{
    use HasFactory;
    protected $table = 'new_gallery';
    protected $fillable = ['imgHeading', 'image'];
}
