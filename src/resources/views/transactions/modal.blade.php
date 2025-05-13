{{-- 評価モーダル --}}
<div id="evaluationModal" class="modal">
    <div class="modal-content">
        <span id="closeModal" class="close">&times;</span>

        {{-- タイトル --}}
        <h2 class="modal-title">取引が完了しました。</h2>

        {{-- 区切り線 --}}
        <hr class="modal-divider">

        {{-- サブタイトル --}}
        <p class="modal-subtitle">今回の取引相手はどうでしたか？</p>

        {{-- 星評価 --}}
        <div class="stars">
            <img class="star" data-value="1" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="2" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="3" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="4" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="5" src="{{ asset('images/modal_star_empty.png') }}">
        </div>

        {{-- 区切り線 --}}
        <hr class="modal-divider">

        <form id="ratingForm" method="POST" action="{{ route('transactions.rate', $transaction->id) }}">
            @csrf
            <input type="hidden" id="ratingValue" name="rating" value="0">
            <button type="submit" class="submit-button">送信する</button>
        </form>
    </div>
</div>
