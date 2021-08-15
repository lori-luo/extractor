<tr>
    <th scope="row">

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $article->id }}" wire:change="$emit('selectedArticle',{{ $article->id }},$event.target.checked)" {{ $is_selected ? 'checked' :'' }}>
        </div>
    </th>
    <td style="width:50%">
        <small>
            {{--
            <span>
                <img class="d-inline" src="{{ asset('images/flags/png/'. strtolower($article->journal_country) .'.png') }}" alt="">
            </span> |
            --}}
            @if(is_null($article->abstract))
            <span class="badge bg-danger">No abstract</span> -
            @endif

            <a class="title_link" href="{{ route('json_article.data.row',$article) }}" target="_blank">
                {!! $article->title !!}

            </a>
        </small>

    </td>
    <td style="width:0%">
        @foreach($article->subject_obj() as $subject)

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

        @if($article->subject <> $article->subject_orig)
            <div>
                <a href="#" wire:click.prevent="reset_subject">
                    <span class="badge bg-dark">Reset</span>
                </a>
            </div>
            @endif

    </td>
    <td style="width:10%">
        @if($article->keyword_obj())
        @foreach($article->keyword_obj() as $keyword)
        <span class="tooltipx badge rounded-pill bg-info text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $keyword }}">
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

        @if($article->keywords <> $article->keywords_orig)
            <div>
                <a href="#" wire:click.prevent="reset_keywords">
                    <span class="badge bg-dark">Reset</span>
                </a>
            </div>
            @endif

    </td>
    <td class="text-nowrap">
        <small>{{ $article->created_at->format('M-d-Y') }}</small>
    </td>
    <td class="text-nowrap">
        <small>{{ $article->created_date->format('M-d-Y') }}</small>

    </td>
    <td class="text-nowrap">
        <small>{{ $article->last_updated->format('M-d-Y') }}</small>
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