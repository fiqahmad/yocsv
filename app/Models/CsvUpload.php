<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvUpload extends Model
{
    protected $fillable = [
        'file_name',
        'status',
        'total_rows',
        'inserted_rows',
        'updated_rows',
        'error_rows',
        'error_messages',
    ];
}
