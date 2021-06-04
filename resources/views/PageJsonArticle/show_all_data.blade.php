<x-app-layout>
    <x-slot name="header">
        <x-json-article-sub-links />
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="table-responsive">
                    <nav class="navbar navbar-light bg-light">
                        <div class="container-fluid">
                            <a class="navbar-brand">Navbar</a>
                            <form class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Search</button>
                            </form>
                        </div>
                    </nav>
                    <table class="table table-sm text-nowrap table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Abstract</th>
                                <th scope="col">Identifier List</th>
                                <th scope="col">Author List</th>
                                <th scope="col">Journal Volume</th>
                                <th scope="col">Journal #</th>
                                <th scope="col">Journal Country</th>
                                <th scope="col">Journal License</th>
                                <th scope="col">Journal ISSNS</th>
                                <th scope="col">Journal Publisher</th>
                                <th scope="col">Journal Language</th>
                                <th scope="col">Journal Title</th>

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
                                    {{ $article->id }}
                                </th>
                                <td>
                                    {{ $article->title }}
                                </td>
                                <td>
                                    {{ $article->abstract }}
                                </td>
                                <td>
                                    {{ $article->identifier_list }}
                                </td>
                                <td>
                                    {{ $article->author_list }}
                                </td>
                                <td>
                                    {{ $article->journal_volume }}
                                </td>
                                <td>
                                    {{ $article->journal_number }}
                                </td>

                                <td>
                                    {{ $article->journal_country }}
                                </td>
                                <td>
                                    {{ $article->journal_license }}
                                </td>
                                <td>
                                    {{ $article->journal_issns }}
                                </td>
                                <td>
                                    {{ $article->journal_publisher }}
                                </td>
                                <td>
                                    {{ $article->journal_language }}
                                </td>
                                <td>
                                    {{ $article->journal_title }}
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