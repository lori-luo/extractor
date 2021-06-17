<div>



    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-danger" wire:click="delete_selected">Delete</button>
            </div>
            <div class="collapse navbar-collapse ml-2" id="navbarSupportedContent">
                <form class="d-flex" wire:submit.prevent="re_search">
                    <input wire:model.defer="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">

                    <div class="input-group mr-2">
                        <label class="input-group-text" for="inputGroupSelect01">File</label>
                        <select class="form-select" wire:model.defer="selected_file">
                            @foreach($option_files as $file)
                            <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <button wire:loading class="btn btn-primary mb-2" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Searching...
    </button>

    <table class="table table-sm table-hover">
        <thead class="table-primary">
            <tr>
                <th scope="col">
                </th>
                <th scope="col" style="width:50%">Title</th>
                <th scope="col" style="width:10%">Subjects</th>
                <th scope="col" style="width:10%">Keywords</th>
                <th scope="col">Imported</th>
                <th scope="col">Created</th>
                <th scope="col">Updated</th>
                <th scope="col">Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            @livewire('row-show-data-article',['article' => $article,'is_selected'=>$is_selected],key($article->article_id))
            @endforeach
        </tbody>
    </table>

    <div style="height:30px;">
        {{ $articles->links() }}
    </div>


</div>