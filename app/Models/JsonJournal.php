<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonJournal extends Model
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

    public function editorial_obj()
    {
        return json_decode($this->editorial);
    }

    public function pid_scheme_obj()
    {
        return json_decode($this->pid_scheme);
    }

    public function copyright_obj()
    {
        return json_decode($this->copyright);
    }

    public function plagiarism_obj()
    {
        return json_decode($this->plagiarism);
    }

    public function language_obj()
    {
        return json_decode($this->language);
    }

    public function article_obj()
    {
        return json_decode($this->article);
    }

    public function institution_obj()
    {
        return json_decode($this->institution_);
    }

    public function preservation_obj()
    {
        return json_decode($this->preservation);
    }

    public function license_obj()
    {
        return json_decode($this->license);
    }

    public function ref_obj()
    {
        return json_decode($this->ref);
    }

    public function publisher_obj()
    {
        return json_decode($this->publisher);
    }

    public function other_charges_obj()
    {
        return json_decode($this->other_charges);
    }
}
