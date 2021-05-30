<x-app-layout>
    <x-slot name="header">
        <x-xml-pub-med-sub-links />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <x-upload-file />
                <h1>List of Files here</h1>
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
                                <button type="button" class="btn btn-success">Read</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>