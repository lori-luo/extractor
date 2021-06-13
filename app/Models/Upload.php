<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    use HasFactory;


    protected $casts = [
        'date_modified' => 'datetime:Y-m-d',
    ];



    protected $fillable = [
        'file_name',
    ];

    public function import_duration()
    {
        if (is_null($this->import_start)) {
            return '';
        }
        $startTime = Carbon::parse($this->import_start);
        $endTime = Carbon::parse($this->import_end);

        $totalDuration =  $startTime->diff($endTime)->format('%I:%S') . " mins";


        return $totalDuration;
    }
}
