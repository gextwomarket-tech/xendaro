<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'imported_rows',
        'failed_rows',
        'status',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function failedRows()
    {
        return $this->hasMany(FailedImportRow::class);
    }
}
