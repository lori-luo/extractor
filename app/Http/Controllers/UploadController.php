<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $upload = new Upload();

        $file = $request->file('file');
        $full_file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();


        $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $new_file_name = $file_name . "_" . uniqid() . "_" . Str::random(40) . "." . $file_ext;
        $file->storeAs($file_ext, $new_file_name);

        $upload->file_name = $file_name;
        $upload->file_type = $file->getClientOriginalExtension();
        $upload->new_file_name = $new_file_name;
        $upload->save();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
