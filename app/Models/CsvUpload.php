<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CsvUpload extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'status',
        'total_rows',
        'inserted_rows',
        'updated_rows',
        'error_rows',
        'error_messages',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
