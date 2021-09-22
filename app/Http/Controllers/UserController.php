<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use model 最初のAppをappにするとNot Foundになる
use App\Models\Upload; // モデル
use App\Models\Payment;


class UserController extends Controller
{

    public function index()
    {
        // paypay決済完了ポーリング・チェック
        Payment::paypay();

        // アップロードファイル一覧を取得
        $uploads = Upload::orderby('created_at', 'desc')->get();
        return view('index', compact('uploads'));
    }
}
