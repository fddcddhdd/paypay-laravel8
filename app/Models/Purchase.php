<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    // MassAssignment(INSERT/UPDATEで入力できるカラムを指定。$fillable=ホワイトリスト、$guarded=ブラックリスト)
    protected $guarded = array('id');
    
    // 購入者
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // 購入したファイル
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}