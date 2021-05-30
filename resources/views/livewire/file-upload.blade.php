<div>
    <form wire:submit.prevent="fileUpload" id="form-upload" enctype="multipart/form-data">
        @if($is_uploading)
        <h1>Uploading-{{ $upload_status }}</h1>
        @endif

        <div class="form-group">
            <input type="file" name="filename" class="form-control" wire:model="filename" />
            <input type="text" name="ss" class="form-control" wire:model="ss" />
        </div>

        <button type="submit">Submit</button>
    </form>
</div>