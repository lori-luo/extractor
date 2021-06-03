<x-app-layout>
    <x-slot name="header">
        <x-xml-pub-med-sub-links />
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <x-upload-file :category="'PubMed'" />
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
                        @foreach($xmls as $xml)
                        <tr>
                            <th scope="row">{{ $xml->id }}</th>
                            <td>{{ $xml->file_name }}</td>
                            <td>{{ $xml->created_at->diffForHumans() }}</td>
                            <td>
                                <livewire:xml-file-row-action :xml="$xml" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $xmls->links() }}
            </div>
        </div>
    </div>
</x-app-layout>