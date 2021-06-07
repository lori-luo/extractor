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
                    <table class="table table-sm table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">

                                    </div>
                                </th>
                                <th scope="col">Title</th>
                                <th scope="col">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                            <tr>
                                <th scope="row">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">

                                    </div>
                                </th>
                                <td>
                                    {{ $article->title }}
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                                        <button type="button" class="btn btn-success ">Edit</button>
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </div>
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