<div>
    <div style="height:30px;">
        {{ $journals->links() }}
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                <button type="button" class="btn btn-danger" wire:click="delete_selected">Delete</button>
            </div>
            <div class="collapse navbar-collapse ml-2" id="navbarSupportedContent">
                <form class="d-flex" wire:submit.prevent="re_search">
                    <input wire:model.lazy="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

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
            @foreach($journals as $journal)
            @livewire('row-show-data-journal',['journal' => $journal,'is_selected'=>$is_selected],key($journal->id))
            @endforeach
        </tbody>
    </table>


</div>