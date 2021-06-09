<x-app-layout>
    @section('title',$title)
    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="table-responsive">
                    <main class="container">
                        <div class="row g-5">
                            <div class="col-md-8">
                                <article class="blog-post">
                                    <h2 class="blog-post-title mt-3">
                                        {{ $article->title }}
                                    </h2>
                                    <p class="blog-post-meta">
                                        Created date: {{ date('F-d-Y', strtotime($article->created_date))  }}
                                        <br>
                                        Last updated: {{ date('F-d-Y', strtotime($article->last_updated))  }}
                                        <br><br>
                                        Year/Month:{{ $article->month }}/{{ $article->year }}
                                    </p>
                                    <h3>Abstract</h3>
                                    <p>
                                        {{ $article->abstract }}
                                    </p>
                                    <hr>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="3">Author list</th>
                                            </tr>
                                            <tr>
                                                <th>name</th>
                                                <th>affiliation</th>
                                                <th>orcid_id</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($article->author_obj() as $author)
                                            <tr>
                                                <td>
                                                    @isset($author->name)
                                                    {{ $author->name  }}
                                                    @endisset
                                                </td>
                                                <td>
                                                    @isset($author->affiliation)
                                                    {{ $author->affiliation  }}
                                                    @endisset
                                                </td>
                                                <td>
                                                    @isset($author->orcid_id)
                                                    {{ $author->orcid_id  }}
                                                    @endisset
                                                </td>


                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="3">Link list</th>
                                            </tr>
                                            <tr>
                                                <th>content_type</th>
                                                <th>type</th>
                                                <th>url</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($article->links_obj() as $link)
                                            <tr>
                                                <td>
                                                    @isset($link->content_type)
                                                    {{ $link->content_type  }}
                                                    @endisset
                                                </td>
                                                <td>
                                                    @isset($link->type)
                                                    {{ $link->type  }}
                                                    @endisset
                                                </td>
                                                <td>
                                                    @isset($link->url)
                                                    <a href="{{  $link->url }}" target="_blank">{{ $link->url  }}</a>
                                                    @endisset
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="3">Identifier list</th>
                                            </tr>
                                            <tr>
                                                <th>id</th>
                                                <th>type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($article->identifier_obj() as $idetifier)
                                            <tr>
                                                <td>
                                                    @isset($idetifier->id)
                                                    {{ $idetifier->id  }}
                                                    @endisset
                                                </td>
                                                <td>
                                                    @isset($idetifier->type)
                                                    {{ $idetifier->type  }}
                                                    @endisset
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Journal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Title</th>
                                                <td>
                                                    {{ $article->journal_title }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Publisher</th>
                                                <td>
                                                    {{ $article->journal_publisher }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>ISSNS</th>
                                                <td>
                                                    @foreach($article->issns_obj() as $issns)
                                                    @if($loop->last)
                                                    {{ $issns }}
                                                    @else
                                                    {{ $issns }},
                                                    @endif
                                                    @endforeach

                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Volume</th>
                                                <td>
                                                    {{ $article->journal_volume }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Number</th>
                                                <td>
                                                    {{ $article->journal_number }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Country</th>
                                                <td>
                                                    {{ $article->journal_country }}
                                                </td>
                                            </tr>
                                            @if($article->license_obj())
                                            <tr>
                                                <th>License</th>
                                                <td>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">open_access</th>
                                                                <th scope="col">title</th>
                                                                <th scope="col">type</th>
                                                                <th scope="col">url</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach($article->license_obj() as $license)
                                                            <tr>
                                                                <td>
                                                                    {{ $license->open_access }}
                                                                </td>
                                                                <td>
                                                                    {{ $license->title }}
                                                                </td>
                                                                <td>
                                                                    {{ $license->type }}
                                                                </td>
                                                                <td>
                                                                    <a href="{{ $license->url }}" target="_blank">{{ $license->url }}</a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endif

                                            <tr>
                                                <th>Language</th>
                                                <td>
                                                    @if($article->language_obj())
                                                    @foreach($article->language_obj() as $language)
                                                    @if($loop->last)
                                                    {{ $language }}
                                                    @else
                                                    {{ $language }},
                                                    @endif
                                                    @endforeach
                                                    @endif

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>




                                </article>




                            </div>

                            <div class="col-md-4">
                                <div class="position-sticky" style="top: 2rem;">

                                    <div class="p-4">
                                        <h4 class="fst-italic">Subjects</h4>
                                        <ol class="list-unstyled mb-0">
                                            @if($article->subject_obj())
                                            @foreach($article->subject_obj() as $subject)
                                            <li><a href="#">{{ $subject->term }}</a></li>
                                            @endforeach
                                            @endif
                                        </ol>
                                    </div>
                                    @if(!is_null($article->keyword_obj()))
                                    <div class="p-4">
                                        <h4 class="fst-italic">Keywords</h4>
                                        <ol class="list-unstyled">

                                            @foreach($article->keyword_obj() as $keyword)
                                            <li><a href="#">{{ $keyword }}</a></li>
                                            @endforeach

                                        </ol>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </main>


                </div>

            </div>
        </div>
    </div>
</x-app-layout>