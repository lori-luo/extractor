<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'language'
    ];


    public function file()
    {
        return $this->belongsTo(Upload::class);
    }
}
