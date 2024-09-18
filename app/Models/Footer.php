<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;
    protected $table = 'footers';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'column_1_heading_1',
        'column_1_field_1',
        'column_1_field_2',
        'column_1_field_3',
        'column_1_field_4',
        'column_2_heading_1',
        'column_2_field_1',
        'column_2_field_2',
        'column_2_field_3',
        'column_3_heading_1',
        'column_3_field_1',
        'column_3_field_2',
        'column_3_field_3'
    ];
}
