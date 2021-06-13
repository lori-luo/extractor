<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JsonArticle;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ArticleDatatables extends LivewireDatatable
{

    public $model = JsonArticle::class;

    public function columns()
    {
        return [
            Column::checkbox(),
            NumberColumn::name('id')
                ->label('ID')
                ->sortBy('id'),

            Column::name('title')
                ->label('Title'),

            Column::callback(['id'], function ($article_id) {
                $all_subjects = '';

                $article = JsonArticle::find($article_id);
                $data['article'] = $article;
                $all_subjects = view('segments.subjects_tags', $data)->render();



                return $all_subjects;
            })->label('subject'),

            Column::callback(['id', 'abstract'], function ($article_id, $abstract) {


                $article = JsonArticle::find($article_id);
                $data['article'] = $article;
                $all_keywords = view('segments.keywords_tag', $data)->render();
                return $all_keywords;
            })->label('keywords'),
            Column::delete()->label('Delete'),
        ];
    }

    public function remove_keyword($keyword, JsonArticle $article)
    {

        $new_keywords = [];
        foreach ($article->keyword_obj() as $k) {
            if (!($keyword == $k)) {
                array_push($new_keywords, $k);
            }
        }

        $new_keywords_obj = json_encode($new_keywords);

        $article->keywords = $new_keywords_obj;
        $article->save();


        auth()->user()->logs()->create([
            'action' => 'Removed Article Keyword : ' . $keyword,
            'type' => 'delete-keyword-article',
            'obj' => json_encode([
                'article' => $article,
                'keyword' => $keyword
            ])
        ]);
    }

    public function remove_subject($subject, JsonArticle $article)
    {

        $new_subjects = [];

        foreach ($article->subject_obj() as $s) {


            if (!($subject == $s->term)) {

                $new_subject['code'] = $s->code;
                $new_subject['scheme'] = $s->scheme;
                $new_subject['term'] = $s->term;
                array_push($new_subjects, $new_subject);
            }
        }

        $new_subjects_obj = json_encode($new_subjects);

        $article->subject = $new_subjects_obj;
        $article->save();

        auth()->user()->logs()->create([
            'action' => 'Removed Article Subject : ' . $subject,
            'type' => 'delete-subject-article',
            'obj' => json_encode([
                'article' => $article,
                'subject' => $subject
            ])
        ]);
    }

    /*
    public function render()
    {
        return view('livewire.article-datatables');
    }

    */
}
