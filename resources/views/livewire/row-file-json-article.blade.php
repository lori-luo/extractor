<tr>
    <th scope="row">{{ $article->id }}</th>
    <td>{{ $article->file_name }}</td>
    <td>{{ $article->created_at->diffForHumans() }}</td>
    <td>
        <button type="button" class="btn btn-success" wire:click="read_json_article">Read</button>
        <button type="button" class="btn btn-danger">Delete</button>
        <span wire:loading>Reading Json File</span>
    </td>
</tr>