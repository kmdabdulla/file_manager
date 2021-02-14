<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploads extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'encrypted_file_path',
        'user_id',
    ];

    protected $hidden = [
        'encrypted_file_path',
    ];
}
