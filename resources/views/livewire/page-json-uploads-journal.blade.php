<div>
    <div style="height:30px;">
        {{ $uploads->links() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <td colspan="4">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">Search </span>
                        <input wire:model="search" type="text" class="form-control" placeholder="filename" aria-describedby="addon-wrapping">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">File Name</th>
                <th scope="col">Modified <br> Date</th>
                <th scope="col">Import <br> Duration</th>
                <th scope="col">Record <br> Count</th>
                <th scope="col">Extracted <br> Count</th>
                <th scope="col">New <br> Records</th>
                <th scope="col">Updated <br> Records</th>
                <th scope="col">Actions</th>
                <th scope="col">Export Selection</th>
            </tr>
        </thead>
        <tbody>
            @foreach($uploads as $journal)
            @livewire('row-file-json-journal',['journal' => $journal],key($journal->id))

            @endforeach
        </tbody>
    </table>
    {{ $uploads->links() }}
</div>