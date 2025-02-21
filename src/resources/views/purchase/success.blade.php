{{-- 商品購入成功画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>購入が完了しました！</h1>
    <p>ご購入ありがとうございます。{{ $item->name }} の発送準備を開始します。</p>
    <p>選択した支払い方法: {{ request('payment_method') }}</p>  {{-- 支払い方法を表示 --}}
    <a href="{{ route('items.index') }}" class="btn btn-primary">トップページに戻る</a>
</div>
@endsection