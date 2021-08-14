<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'language',
        'selected'
    ];


    public function file()
    {
        return $this->belongsTo(Upload::class);
    }
}
