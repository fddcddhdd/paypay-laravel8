<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\Purchase;

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

    public function download($id){
        $upload_file = Upload::findOrFail($id);
        // 無料 or 購入済ならダウンロードできる
        if( $upload_file->free_flag == true || !$upload_file->purchase->isEmpty()){
            // 第一引数がstorageファイルのパス。第二引数がダウンロードさせるファイル名
            return \Storage::download('public/'. $upload_file->file_path, $upload_file->file_name);
        }else{
            return back();
        }

    }

    public function store(Request $request)
    {
        // フォームの入力の値をチェック
        $validated = $request->validate([
            'title' => 'required|unique:uploads|max:255',
            'detail' => 'required|max:255',
            'file' => 'required|file',
        ]);
        // ファイルそのものはWebサーバに保存
        $file_name = $request->file('file')->getClientOriginalName();
        //$file_path = Storage::putFile('/uploads', $request->file('file'), 'public');
        $upload_file = $request->file('file');
        $file_path = $upload_file->store('uploads',"public");

        // ファイル名とパスは、DBに保存する。
        $upload = new Upload();
        $upload->title = $request->input('title');
        $upload->detail = $request->input('detail');
        $upload->file_name = $file_name;
        $upload->file_path = $file_path;
        $upload->free_flag = $request->input('free_flag');
        $upload->save();

        // 前の画面に戻る
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function show(Upload $upload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upload $upload)
    {
        //
    }
}
