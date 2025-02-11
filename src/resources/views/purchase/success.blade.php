@extends('layouts.app')

@section('content')
<div class="container">
    <h1>購入が完了しました！</h1>
    <p>ご購入ありがとうございます。商品の発送準備を開始します。</p>
    <a href="{{ route('items.index') }}" class="btn btn-primary">トップページに戻る</a>
</div>
@endsection
