<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use model 最初のAppをappにするとNot Foundになる
use App\Models\Upload; // モデル
use App\Models\Purchase;
use App\Models\User;
use Auth;

// paypay関係
use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\OrderItem;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;


class Payment extends Model
{
    use HasFactory;
    // MassAssignment(INSERT/UPDATEで入力できるカラムを指定。$fillable=ホワイトリスト、$guarded=ブラックリスト)
    protected $guarded = array('id');

    // paypay決済が完了したかチェックする(こちらからポーリングする必要がある)
    // https://paypay.ne.jp/developers-faq/open_payment_api/post-42/
    public static function paypay()
    {
        // ログイン中だったら
        if(Auth::check()){
            // .envファイルに書いておく
            $client = new Client([
                'API_KEY' => env('PAYPAY_API_KEY'),
                'API_SECRET'=> env('PAYPAY_API_SECRET'),
                'MERCHANT_ID'=> env('PAYPAY_MERCHANT_ID')
            ], true);

            // DBから未決済の決済IDがあったら
            $user = User::find(Auth::id());
            if(Purchase::where("user_id", $user->id)->where("paid_flag", false)->exists()){
                $purchase = Purchase::where("user_id", $user->id)->where("paid_flag", false)->firstOrFail();
                $merchantPaymentId = $purchase->merchantPaymentId;        
                //-------------------------------------
                // 決済情報を取得する
                //-------------------------------------
                $QRCodeDetails = $client->code->getPaymentDetails($merchantPaymentId);
                if($QRCodeDetails['resultInfo']['code'] !== 'SUCCESS') {
                    \Session::flash('errors',  '決済情報取得エラー');
                    return;
                }
                // paypay決済が完了したら、DBに書き込む
                if($QRCodeDetails['data']['status'] == 'COMPLETED') {
                    $purchase->update([
                        'paid_flag' => true,
                        'QRCodeDetails' => $QRCodeDetails
                    ]);
                    \Session::flash('success', $purchase->upload->title . 'を購入しました');
                }
            }
        }
        return ;
    }

}
