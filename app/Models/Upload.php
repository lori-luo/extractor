<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;


    protected $casts = [
        'date_modified' => 'datetime:Y-m-d',
    ];



    protected $fillable = [
        'file_name',
    ];
}
