<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use model 最初のAppをappにするとNot Foundになる
use App\Models\User; // userモデル
use App\Models\Upload; // 販売ファイル
use App\Models\Purchase; // 販売履歴

class AdminController extends Controller
{
    public function index()
    {
        // アップロードファイル一覧を取得
        $uploads = Upload::orderby('created_at', 'desc')->get();
        // 管理者以外のユーザ一覧を取得
        $users = User::WHERE('admin', false)->orderby('created_at', 'desc')->get();
        // 販売履歴
        $purchases = Purchase::where('paid_flag', true)->orderby('created_at', 'desc')->with(['user'])->get();
        
        return view('admin', compact('users', 'uploads', 'purchases'));
    }
}
