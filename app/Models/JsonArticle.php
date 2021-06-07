<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonArticle extends Model
{
    use HasFactory;

    public function subject_obj()
    {
        return json_decode($this->subject);
    }

    public function keyword_obj()
    {
        return json_decode($this->keywords);
    }
}
