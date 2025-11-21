<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    protected $table = 'csv_data';

    protected $fillable = [
        'unique_key',
        'product_title',
        'product_description',
        'style',
        'sanmar_mainframe_color',
        'size',
        'color_name',
        'piece_price',
    ];
}
