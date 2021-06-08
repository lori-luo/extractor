<div>
    {{ $articles->links() }}

    <table class="table table-sm table-hover">
        <thead class="table-primary">
            <tr>
                <th scope="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">

                    </div>
                </th>
                <th scope="col">Title</th>
                <th scope="col" style="width:20%">Subjects</th>
                <th scope="col" style="width:20%">Keywords</th>
                <th scope="col">Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            <livewire:row-show-data-article :article="$article" :wire:key="$article->article_id" />
            @endforeach
        </tbody>
    </table>
    {{ $articles->links() }}


</div>