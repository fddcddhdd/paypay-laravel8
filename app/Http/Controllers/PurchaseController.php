<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

// paypay関係
use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\OrderItem;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;

class PurchaseController extends Controller
{
    // 購入処理
    public function purchase($upload_id){

        // 購入するファイル情報を取得
        $upload = Upload::findorFail($upload_id);

        // このユーザが購入済みなら何もしない
        $user = User::find(Auth::id());
        if(Purchase::where("upload_id", $upload_id)->where("user_id", $user->id)->where("paid_flag", true)->exists()){
            return redirect('/')->with('success', "$upload->file_nameは既に購入済みです。");
        }

        // composer require paypayopa/php-sdk

        // .envファイルに書いておく
        $client = new Client([
            'API_KEY' => env('PAYPAY_API_KEY'),
            'API_SECRET'=> env('PAYPAY_API_SECRET'),
            'MERCHANT_ID'=> env('PAYPAY_MERCHANT_ID')
        ], true);

        // paypayの支払いサイトが完了したら、リダイレクトされるURL
        // ブラウザの戻るボタンで戻っても、支払いIDが決済完了になっているので３秒後にリダイレクトされ直すだけ
        $rediect_url = env('PAYPAY_REDIRECT_URL');

        //-------------------------------------
        // 商品情報を生成する
        //-------------------------------------
        $items = (new OrderItem())
            ->setName($upload->title)
            ->setQuantity(1)
            ->setUnitPrice(['amount' => 100, 'currency' => 'JPY']);

        //-------------------------------------
        // QRコードを生成する
        //-------------------------------------
        $payload = new CreateQrCodePayload();
        $payload->setOrderItems($items);
        $payload->setMerchantPaymentId("mpid_".rand());	// 同じidを使いまわさないこと！
        $payload->setCodeType("ORDER_QR");
        $payload->setAmount(["amount" => 100, "currency" => "JPY"]);
        $payload->setRedirectType('WEB_LINK');
        $payload->setIsAuthorization(false);
        $payload->setRedirectUrl($rediect_url);
        $payload->setUserAgent($_SERVER['HTTP_USER_AGENT']);
        $QRCodeResponse = $client->code->createQRCode($payload);
        if($QRCodeResponse['resultInfo']['code'] !== 'SUCCESS') {
            echo("QRコード生成エラー");
            return;
        }
        // 支払いIDはデータベースに保存しておく(まだ決済は未完了)
        $merchantPaymentId = $QRCodeResponse['data']['merchantPaymentId'];
        // 未決済レコードがあったら決済IDをUPDATE、なかったらINSERT
        Purchase::updateOrCreate([
            'upload_id'=>$upload_id,
            'user_id'=>$user->id,
            'paid_flag'=>false,
        ],[
            'upload_id'=>$upload_id,
            'user_id'=>$user->id,
            'paid_flag'=>false,
            'merchantPaymentId'=>$merchantPaymentId,
        ]);

        // paypayの支払いページに行く。支払いが終わったら$payload->setRedirectUrlにリダイレクトされる
        return redirect($QRCodeResponse['data']['url']);


    }
    // 決済完了
    public function thanks()
    {
        // paypay決済完了ポーリング・チェック
        Payment::paypay();

        return redirect('/');
    }
     
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
