@if($article->keyword_obj())
@foreach($article->keyword_obj() as $keyword)
<span class="tooltipx badge rounded-pill bg-info text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $keyword }}">
    <span class="tooltiptext">{{ $keyword }}</span>
    @if(strlen($keyword) >= 20)
    {{ Str::substr($keyword, 0, 20) }} . . .
    @else
    {{ $keyword }}
    @endif

    <button wire:click="remove_keyword('{{ $keyword }}',{{ $article->id }})" type="button" class="close" aria-label="Dismiss">
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