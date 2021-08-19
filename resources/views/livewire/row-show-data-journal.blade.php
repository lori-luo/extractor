<tr>
    <th scope="row">

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $journal->id }}" wire:change="$emit('selectedJournal',{{ $journal->id }},$event.target.checked)" {{ $is_selected ? 'checked' :'' }}>
        </div>
    </th>

    <td style="width:50%">
        @php


        @endphp
        <small>

            <span>
                <img class="d-inline" src="{{ asset('images/flags/png/'. strtolower(json_decode($journal->publisher)->country) .'.png') }}" alt="">
            </span> |

            <a class="title_link" href="{{ route('json_journal.data.row',[$journal,$journal->slug]) }}" target="_blank">
                {{ $journal->title }}
            </a>
        </small>

    </td>
    <td class="text-nowrap">
        <small>
            <table class="table table-sm">
                <tbody>
                    @foreach($journal->language_arr() as $key=>$lang)
                    <tr>
                        <th scope="row">
                            {{ $key }}
                        </th>
                        <td>
                            {{ $lang }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </small>
    </td>
    <td class="text-nowrap">
        <small>{{ $journal->eissn }}</small>
    </td>
    <td class="text-nowrap">
        <small>{{ $journal->pissn }}</small>
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
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-danger" wire:click="delete_article">Confirm</button>
                <button type="button" class="btn btn-warning" wire:click="cancel_confirm">CANCEL</button>
            </div>


            @else
            <button type="button" class="btn btn-danger" wire:click="delete_confirm">Delete</button>
            @endif

        </div>
    </td>
</tr>