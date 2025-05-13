@component('mail::message')
# 取引完了のお知らせ

{{ $buyer->name }} 様が商品 **「{{ $item->name }}」** の取引を完了しました。

### 商品情報
- 商品名: {{ $item->name }}
- 金額: ¥{{ number_format($item->price) }}

### 購入者情報
- お名前: {{ $buyer->name }}
- メールアドレス: {{ $buyer->email }}

取引詳細は、マイページの「取引中の商品」からご確認ください。

@component('mail::button', ['url' => url('/transactions/' . $transaction->id)])
取引詳細を見る
@endcomponent

ありがとうございます。  
{{ config('app.name') }}
@endcomponent
