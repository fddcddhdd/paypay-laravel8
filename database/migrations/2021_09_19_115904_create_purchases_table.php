<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            // 外部キーも制約もメソッドで指定できる！
            $table->foreignId('user_id')->constrained()->comment('購入者ユーザID');
            $table->foreignId('upload_id')->constrained()->comment('購入されたアップロードファイルID');
            $table->string('merchantPaymentId')->comment('paypay決済ID');
            $table->boolean('paid_flag')->default(false)->comment('paypay決済完了フラグ');
            $table->text('QRCodeDetails')->nullable()->comment('paypay決済完了のレスポンスJSON');
            
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
        Schema::dropIfExists('purchases');
    }
}
