<tr>
    <th scope="row">

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $journal->id }}" wire:change="$emit('selectedJournal',{{ $journal->id }},$event.target.checked)" {{ $is_selected ? 'checked' :'' }}>
        </div>
    </th>

    <td style="width:50%">
        <small>
            <a href="{{ route('json_journal.data.row',$journal) }}" target="_blank">
                {{ $journal->title }}
            </a>
        </small>

    </td>

    <td style="width:10%">
        @if($journal->subject_obj())
        @foreach($journal->subject_obj() as $subject)
        <span class="tooltipx badge rounded-pill bg-primary">
            <span class="tooltiptext">{{ $subject->term }}</span>
            @if(strlen($subject->term) >= 20)
            {{ Str::substr($subject->term, 0, 20) }} . . .
            @else
            {{ $subject->term }}
            @endif
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

    <td style="width:10%">
        @if($journal->keyword_obj())
        @foreach($journal->keyword_obj() as $keyword)
        <span class="tooltipx badge rounded-pill bg-info text-dark">
            <span class="tooltiptext">{{ $keyword }}</span>
            @if(strlen($keyword) >= 20)
            {{ Str::substr($keyword, 0, 20) }} . . .
            @else
            {{ $keyword }}
            @endif
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

    <td class="text-nowrap">
        <small>{{ $journal->created_at->format('M-d-Y') }}</small>
    </td>
    <td class="text-nowrap">
        <small>{{ $journal->created_date->format('M-d-Y') }}</small>

    </td>
    <td class="text-nowrap">
        <small>{{ $journal->last_updated->format('M-d-Y') }}</small>
    </td>
    <td>
        <div class="btn-group btn-group-sm" role="group">
            @if($show_delete_confirm)
            <div class="alert alert-primary mt-1" role="alert">
                <span>
                    SURE?
                </span>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-danger" wire:click="delete_article">YES</button>
                    <button type="button" class="btn btn-warning" wire:click="cancel_confirm">CANCEL</button>
                </div>
            </div>

            @else
            <button type="button" class="btn btn-danger" wire:click="delete_confirm">Delete</button>
            @endif

        </div>
    </td>
</tr>