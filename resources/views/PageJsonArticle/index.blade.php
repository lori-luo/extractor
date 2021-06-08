<x-app-layout>
    <x-slot name="header">
        <x-json-article-sub-links />
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-upload-file :category="'Article'" />
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">File Name</th>
                            <th scope="col">Upload Date</th>
                            <th scope="col">Record <br> Count</th>
                            <th scope="col">Extracted <br> Count</th>
                            <th scope="col">New <br> Records</th>
                            <th scope="col">Updated <br> Records</th>
                            <th scope="col">Actions</th>
                            <th scope="col">
                                Export Selection
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                        <livewire:row-file-json-article :article="$article" />
                        @endforeach
                    </tbody>
                </table>
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>