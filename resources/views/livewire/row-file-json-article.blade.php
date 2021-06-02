<tr>
    <th scope="row">{{ $article->id }}</th>
    <td>{{ $article->file_name }}</td>
    <td>{{ $article->created_at->diffForHumans() }}</td>
    <td>{{ $article->original_record_count }}</td>
    <td>{{ $article->extracted_record_count }}</td>
    <td>

        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-info" wire:click="read_json_article">Import</button>
            </div>
            <div class="col">
                <div class="input-group">
                    <select class="form-select" id="export_qty" name="export_qty" wire:model="export_qty" aria-label="Example select with button addon">
                        <option value="1">1-20k</option>
                        <option value="2">20k-40k</option>
                        <option value="3">40k-60k</option>
                        <option value="4">60k-80k</option>
                        <option value="5">80k-100k</option>
                    </select>
                    <button class="btn btn-outline-secondary" type="button" wire:click="export">Export</button>
                </div>
            </div>
        </div>


        <h1>{{ $test }}</h1>

        <span wire:loading>Reading Json File</span>
    </td>
</tr>