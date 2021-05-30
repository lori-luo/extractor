<div>
    <form action="{{ route('xml_pub_med.upload.store') }}" id="form-upload" enctype="multipart/form-data" method="post">
        @csrf
        <h1>Upload</h1>
        <h1>Category: {{ $category }}</h1>
        <div class="form-group">
            <input type="file" name="file" class="form-control" />
            <input type="hidden" name="file_category" value="{{ $category }}" />
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>