<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('タイトル');
            $table->string('detail')->nullable()->comment('詳細');
            $table->string('file_path')->comment('アップロードされたファイルのパス');
            $table->string('file_name')->comment('ハッシュ化される前のファイル名');
            $table->string('free_flag')->nullable()->default(false)->comment('無料フラグ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
