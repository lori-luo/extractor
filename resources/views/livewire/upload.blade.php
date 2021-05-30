<div>

    <form wire:submit.prevent="fileUpload" id="form-upload" enctype="multipart/form">
        <div class="form-group">
            <input type="file" name="filename" class="form-control" wire:model="filename" />
        </div>

        <button type="submit">Submit</button>
    </form>
</div>