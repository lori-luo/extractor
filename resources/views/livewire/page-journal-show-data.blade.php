<div>


    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

            <div class="collapse navbar-collapse ml-2" id="navbarSupportedContent">

            </div>
        </div>
    </nav>

    <div class="row m-2">
        <div class="col-sm-1">
            <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                <button type="button" class="btn btn-danger" wire:click="delete_selected">Delete</button>
            </div>
        </div>
        <div class="col">
            <form class="d-flex" wire:submit.prevent="re_search">
                <input wire:model.lazy="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
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

                                    {{--

                                    @foreach($search_langs as $lang)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input wire:key="check-lang-{{ $lang->id }}" id="check-lang-{{ $lang->id }}" class="form-check-input" type="checkbox" wire:click="lang_clicked_search_pre({{ $lang->id }},$event.target.checked)" value="{{ $lang->id }}" {{ $lang->selected ? 'checked' : '' }}>
                                    <label class="form-check-label" for="check-lang-{{ $lang->id }}">
                                        {{ $lang->code }}
                                    </label>
                        </div>
                        </th>
                        <td>{{ $lang->language }} </td>
                        </tr>
                        @endforeach
                        --}}
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

</div>

<table class="table table-sm table-hover">
    <thead class="table-primary">
        <tr>
            <th scope="col">

            </th>
            <th scope="col" style="width:50%">Title</th>
            <th scope="col">ISSN</th>
            <th scope="col">PISSN</th>
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

<div style="height:30px;">
    {{ $journals->links() }}
</div>


</div>