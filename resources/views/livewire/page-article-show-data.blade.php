<div class="pb-5">
    <div class="row m-2">
        <div class="col-sm-1">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-danger" wire:click="delete_selected">Delete</button>
            </div>
        </div>
        <div class="col">
            <input wire:model.defer="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        </div>
        <div class="col">
            <div class="input-group mr-2">
                <label class="input-group-text" for="inputGroupSelect01">File</label>
                <select class="form-select" wire:model.defer="selected_file">
                    <option value="0">ALL batches</option>
                    @foreach($option_files as $file)
                    <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-select-lang">
                Select Languages
            </button>

            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="modal-select-lang">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Select Languages
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div style="height: 400px;" class="modal-body overflow-auto">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Languages
                                        </th>
                                        <th>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button wire:click="lang_reset_arr" type="button" class="btn btn-danger">
                                                    Reset
                                                </button>
                                                <button wire:click="lang_reset_arr('select')" type="button" class="btn btn-warning">
                                                    Select All
                                                </button>
                                                <button wire:click="lang_reset_arr('unselect')" type="button" class="btn btn-success">
                                                    Unselect All
                                                </button>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody wire:loading.remove>

                                    @foreach($search_langs_arr as $key=>$l)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input wire:key="check-lang-arr-{{ $key }}" id="check-lang-arr-{{ $key }}" class="form-check-input" type="checkbox" wire:click="lang_clicked_search_pre_arr({{ $key }},$event.target.checked)" value="{{ $key }}" {{ $l['selected'] ? 'checked' : '' }}>
                                                <label class="form-check-label" for="check-lang-arr-{{ $key }}">
                                                    {{ $l['code'] }}
                                                </label>
                                            </div>
                                        </th>
                                        <td>{{ $l['language'] }} </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div wire:loading>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Searching...
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col">
            <button wire:loading.remove class="btn btn-success" wire:click="re_search">Search</button>
            <button wire:loading class="btn btn-primary mb-2" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Searching...
            </button>
        </div>
    </div>





    <table class="table table-sm table-hover">
        <thead class="table-primary">
            <tr>
                <th scope="col">
                </th>
                <th scope="col" style="width:50%">Title</th>
                <th scope="col">Languages</th>
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