@extends('layouts.app')

@section('content')
<div class="container">
    <h1>購入がキャンセルされました</h1>
    <p>再度購入をお試しください。</p>
    <a href="{{ route('items.index') }}" class="btn btn-primary">トップページに戻る</a>
</div>
@endsection
