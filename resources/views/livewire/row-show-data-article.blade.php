<tr>
    <th scope="row">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $article->id }}" wire:change="$emit('selectedArticle',{{ $article->id }},$event.target.checked)" {{ $is_selected ? 'checked' :'' }}>
        </div>
    </th>
    <td>
        <small>
            <a href="{{ route('json_article.data.row',$article) }}" target="_blank">
                {{ $article->title }}
            </a>
        </small>

    </td>
    <td style="width:20%">
        @foreach($article->subject_obj() as $subject)
        <span class="badge rounded-pill bg-primary">
            {{ $subject->term }}
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
    <td style="width:20%">
        @if($article->keyword_obj())
        @foreach($article->keyword_obj() as $keyword)
        <span class="badge rounded-pill bg-info text-dark">
            {{ $keyword }}
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