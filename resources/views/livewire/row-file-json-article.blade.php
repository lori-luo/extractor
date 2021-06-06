<tr>
    <th scope="row">{{ $article->id }}</th>
    <td>{{ $article->file_name }}</td>
    <td>{{ $article->created_at->diffForHumans() }}</td>
    <td>{{ $article->original_record_count }}</td>
    <td>{{ $article->extracted_record_count }}</td>
    <td>{{ $article->new_record_count }}</td>
    <td>{{ $article->updated_record_count }}</td>
    <td>

        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-sm btn-info" wire:click="read_json_article">Import</button>
                <button type="button" class="btn btn-sm btn-warning" wire:click="read_json_article_force">Force Import</button>
            </div>
            <div class="col">
                <div class="input-group input-group-sm">

                    <select class="form-select" id="export_qty" name="export_qty" wire:model="export_qty">
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

                    <button class="btn btn-outline-secondary" type="button" wire:click="export">Export</button>
                </div>

            </div>
        </div>

        <span wire:loading wire:target="read_json_article">Import Json File</span>
        <span wire:loading wire:target="read_json_article_force">Force:Import Json File</span>
        <span wire:loading wire:target="export">Exporting Json File</span>


    </td>
    <td>
        <input type="radio" id="all-{{ $article->id }}" name="sel_typ-{{ $article->id }}" value="1" wire:model="sel_type">
        <label for="all-{{ $article->id }}">All</label>
        <input type="radio" id="new-{{ $article->id }}" name="sel_typ-{{ $article->id }}" value="2" wire:model="sel_type">
        <label for="new-{{ $article->id }}">New</label>
        <input type="radio" id="updated-{{ $article->id }}" name="sel_typ-{{ $article->id }}" value="3" wire:model="sel_type">
        <label for="updated-{{ $article->id }}">Updated</label>


    </td>
</tr>