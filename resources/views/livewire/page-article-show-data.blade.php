<div>

    {{ $articles->links() }}

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-danger" wire:click="delete_selected">Delete</button>
                <button type="button" class="btn btn-warning">Reset</button>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
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
                <th scope="col" style="width:20%">Subjects</th>
                <th scope="col" style="width:20%">Keywords</th>
                <th scope="col">Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            <livewire:row-show-data-article :article="$article" :wire:key="$article->article_id" />
            @endforeach
        </tbody>
    </table>
    {{ $articles->links() }}


</div>