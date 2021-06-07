<tr>
    <th scope="row">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        </div>
    </th>
    <td>

        @if($edit)
        <div>
            edit here
        </div>
        @else
        <small>{{ $article->title }}</small>
        @endif


    </td>
    <td>
        @foreach($article->subject_obj() as $subject)
        <span class="badge rounded-pill bg-primary">
            {{ $subject->term }}
            <button wire:click="remove_subject('{{ $subject->term }}')" type="button" class="close" aria-label="Dismiss">
                <span aria-hidden="true">&times;</span>
            </button>
        </span>
        @endforeach

    </td>
    <td>
        @if($article->keyword_obj())
        @foreach($article->keyword_obj() as $keyword)
        <span class="badge rounded-pill bg-info text-dark">
            {{ $keyword }}
            <button type="button" class="close" aria-label="Dismiss">
                <span aria-hidden="true">&times;</span>
            </button>
        </span>
        @endforeach

        @endif


    </td>
    <td>
        @if($edit)
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-primary" wire:click="save_edit">Save</button>
            <button type="button" class="btn btn-danger">Cancel</button>
        </div>
        @else
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-success" wire:click="show_edit">Edit</button>
            <button type="button" class="btn btn-danger">Delete</button>
        </div>
        @endif

    </td>
</tr>