<tr>
    <th scope="row">{{ $journal->id }}</th>
    <td>{{ $journal->file_name }}</td>
    <td>{{ $journal->date_modified->format('M-d-Y') }}</td>
    <td>{{ $journal->import_duration() }}</td>
    <td>{{ $journal->original_record_count }}</td>
    <td>{{ $journal->extracted_record_count }}</td>
    <td>{{ $journal->new_record_count }}</td>
    <td>{{ $journal->updated_record_count }}</td>
    <td>
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-sm btn-info" wire:click="read_json_journal">Import</button>
                <button type="button" class="btn btn-sm btn-warning" wire:click="read_json_journal_force">Force Import</button>
            </div>

            <div class="col">

                <div class="input-group input-group-sm">
                    @if($export_qty_category==1)
                    <select class="form-select" name="export_qty" wire:model="export_qty">
                        <option value="1">1-10k</option>
                        <option value="2">10k-20k</option>
                        <option value="3">20k-30k</option>
                        <option value="4">30k-40k</option>
                        <option value="5">40k-50k</option>
                        <option value="6">50k-60k</option>
                        <option value="7">60k-70k</option>
                        <option value="8">70k-80k</option>
                        <option value="9">80k-90k</option>
                        <option value="10">90k-100k</option>
                    </select>
                    @elseif($export_qty_category==2)
                    <select class="form-select" id="export_qty" name="export_qty" wire:model="export_qty">
                        <option value="1">1-20k</option>
                        <option value="2">20k-40k</option>
                        <option value="3">40k-60k</option>
                        <option value="4">60k-80k</option>
                        <option value="5">80k-100k</option>
                    </select>
                    @endif
                    <button class="btn btn-outline-secondary" type="button" wire:click="export">Export</button>
                </div>
            </div>
        </div>

        <span wire:loading wire:target="read_json_journal">Import Json File</span>
        <span wire:loading wire:target="read_json_journal_force">Force:Import Json File</span>
        <span wire:loading wire:target="export">Exporting Json File</span>

    </td>

    <td>
        <input type="radio" id="all-{{ $journal->id }}" name="sel_typ-{{ $journal->id }}" value="1" wire:model="sel_type">
        <label for="all-{{ $journal->id }}">All</label>
        <input type="radio" id="new-{{ $journal->id }}" name="sel_typ-{{ $journal->id }}" value="2" wire:model="sel_type">
        <label for="new-{{ $journal->id }}">Updated</label>
    </td>
</tr>