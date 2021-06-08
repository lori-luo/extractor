<tr>
    <th scope="row">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        </div>
    </th>

    <td>
        <small>{{ $journal->title }}</small>
    </td>

    <td style="width:20%">
        @if($journal->subject_obj())
        @foreach($journal->subject_obj() as $subject)
        <span class="badge rounded-pill bg-primary">
            {{ $subject->term }}
            <button wire:click="remove_subject('{{ $subject->term }}')" type="button" class="close" aria-label="Dismiss">
                <span aria-hidden="true">&times;</span>
            </button>
        </span>
        @endforeach
        @endif

        @if($journal->subject <> $journal->subject_orig)
            <div>
                <a href="#" wire:click.prevent="reset_subject">
                    <span class="badge bg-dark">Reset</span>
                </a>
            </div>
            @endif


    </td>

    <td style="width:20%">
        @if($journal->keyword_obj())
        @foreach($journal->keyword_obj() as $keyword)
        <span class="badge rounded-pill bg-info text-dark">
            {{ $keyword }}
            <button wire:click="remove_keyword('{{ $keyword }}')" type="button" class="close" aria-label="Dismiss">
                <span aria-hidden="true">&times;</span>
            </button>
        </span>
        @endforeach
        @endif

        @if($journal->keywords <> $journal->keywords_orig)
            <div>
                <a href="#" wire:click.prevent="reset_keywords">
                    <span class="badge bg-dark">Reset</span>
                </a>
            </div>
            @endif
    </td>
    <td>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-danger">Delete</button>
        </div>
    </td>
</tr>