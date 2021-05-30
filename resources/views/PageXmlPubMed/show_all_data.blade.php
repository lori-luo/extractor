<x-app-layout>
    <x-slot name="header">
        <x-xml-pub-med-sub-links />
    </x-slot>

    <div class="py-12">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h1>List of Data here</h1>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">PMID</th>
                                <th scope="col">Status</th>
                                <th scope="col">Owner</th>
                                <th scope="col">Version</th>
                                <th scope="col">Date Completed</th>
                                <th scope="col">Date Revised</th>
                                <th scope="col">Pub Model</th>
                                <th scope="col">ISSN</th>
                                <th scope="col">ISSN Type</th>
                                <th scope="col">Cited Medium</th>
                                <th scope="col">Volume</th>
                                <th scope="col">Issue</th>
                                <th scope="col">Pub Date</th>
                                <th scope="col">Title</th>
                                <th scope="col">ISO Abbr.</th>
                                <th scope="col">Article Title</th>
                                <th scope="col">MedlinePgn</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                            <tr>
                                <th scope="row">
                                    {{ $article->pmid }}
                                </th>
                                <td>
                                    {{ $article->status }}
                                </td>
                                <td>
                                    {{ $article->owner }}
                                </td>
                                <td>
                                    {{ $article->version }}
                                </td>
                                <td>
                                    {{ $article->date_completed }}
                                </td>
                                <td>
                                    {{ $article->date_revised }}
                                </td>
                                <td>
                                    {{ $article->pub_model }}
                                </td>
                                <td>
                                    {{ $article->issn }}
                                </td>
                                <td>
                                    {{ $article->issntype }}
                                </td>
                                <td>
                                    {{ $article->cited_medium }}
                                </td>
                                <td>
                                    {{ $article->volume }}
                                </td>
                                <td>
                                    {{ $article->issue }}
                                </td>
                                <td>
                                    {{ $article->pub_date }}
                                </td>
                                <td>
                                    {{ $article->title }}
                                </td>
                                <td>
                                    {{ $article->iso_abbreviation }}
                                </td>
                                <td>
                                    {{ $article->article_title }}
                                </td>
                                <td>
                                    {{ $article->medlinepgn }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>