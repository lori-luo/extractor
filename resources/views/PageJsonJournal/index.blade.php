<x-app-layout>
    <x-slot name="header">
        <x-json-journal-sub-links />
    </x-slot>

    <div class="mt-2">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-upload-file :category="'Journal'" />
                <table class="table">
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
                            <th scope="col">Export Selection</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uploads as $journal)
                        <livewire:row-file-json-journal :journal="$journal" />
                        @endforeach
                    </tbody>
                </table>
                {{ $uploads->links() }}
            </div>
        </div>
    </div>
</x-app-layout>