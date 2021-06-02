<x-app-layout>
    <x-slot name="header">
        <x-json-journal-sub-links />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-upload-file :category="'Journal'" />
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">File Name</th>
                            <th scope="col">Upload Date</th>
                            <th scope="col">Actions</th>
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