<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Component
{


    public $filename;
    public $ss;
    public $is_uploading;
    public $upload_status;

    use WithFileUploads;

    public function mount()
    {
        $this->is_uploading = false;
        $this->upload_status = "Start";
    }

    public function fileUpload()
    {
        $this->is_uploading = true;

        $this->validate([
            'filename' => 'required'
        ]);

        $this->upload_status = "Validated";




        $path = $this->filename->store('xml');
        dd($path);
        $data['file_name'] = $path;

        // $extension = $this->filename->getClientOriginalExtension();
        // $filename = 'sample_file.' . $extension;
        //  Storage::disk('local')->putFileAs('/files/', $this->filename, $filename);


        $this->upload_status = "Stored";


        Upload::insert($data);
        //  $this->upload_status = $extension;
        // $filename=$this->filename->store('files','public');
        //dd($path);

        $this->is_uploading = false;
    }
    public function render()
    {
        return view('livewire.file-upload');
    }
}
