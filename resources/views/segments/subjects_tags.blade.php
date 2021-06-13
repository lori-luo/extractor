@foreach($article->subject_obj() as $subject)

<span class="tooltipx badge rounded-pill bg-primary">
    <span class="tooltiptext">{{ $subject->term }}</span>
    @if(strlen($subject->term) >= 20)
    {{ Str::substr($subject->term, 0, 20) }} . . .
    @else
    {{ $subject->term }}
    @endif
    <button wire:click="remove_subject('{{ $subject->term }}',{{ $article->id }})" type="button" class="close" aria-label="Dismiss">
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