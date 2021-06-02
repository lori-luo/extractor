<tr>
    <th scope="row">{{ $journal->id }}</th>
    <td>{{ $journal->file_name }}</td>
    <td>{{ $journal->created_at->diffForHumans() }}</td>
    <td>
        <div class="input-group">
            <select class="form-select" name="export_qty" wire:model="export_qty" aria-label="Example select with button addon">
                <option value="1">1-20k</option>
                <option value="2">20k-40k</option>
                <option value="3">40k-60k</option>
                <option value="4">60k-80k</option>
                <option value="5">80k-100k</option>
            </select>
            <button class="btn btn-outline-secondary" type="button" wire:click="export">Export</button>
        </div>

        {{ $test }}

    </td>
</tr>