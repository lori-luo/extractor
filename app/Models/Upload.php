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

    public function size()
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
