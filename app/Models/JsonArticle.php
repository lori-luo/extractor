<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonArticle extends Model
{
    use HasFactory;

    protected $casts = [
        'created_date' => 'datetime:Y-m-d',
        'last_updated' => 'datetime:Y-m-d',
    ];



    public function subject_obj()
    {
        return json_decode($this->subject);
    }

    public function keyword_obj()
    {
        return json_decode($this->keywords);
    }

    public function author_obj()
    {
        return json_decode($this->author_list);
    }

    public function links_obj()
    {
        return json_decode($this->link_list);
    }

    public function identifier_obj()
    {
        return json_decode($this->identifier_list);
    }

    public function issns_obj()
    {
        return json_decode($this->journal_issns);
    }

    public function license_obj()
    {
        return json_decode($this->journal_license);
    }

    public function language_obj()
    {
        return json_decode($this->journal_language);
    }
}
