<div>

    {{ $journals->links() }}
    <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
        <button type="button" class="btn btn-danger">Delete</button>
    </div>
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
            @foreach($journals as $journal)
            <livewire:row-show-data-journal :journal="$journal" :wire:key="$journal->journal_id" />
            @endforeach
        </tbody>
    </table>
    {{ $journals->links() }}


</div>