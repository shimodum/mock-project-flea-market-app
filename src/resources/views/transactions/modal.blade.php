{{-- 評価モーダル --}}
<div id="evaluationModal" class="modal">
    <div class="modal-content">
        <span id="closeModal" class="close">&times;</span>
        <h2>取引が完了しました。</h2>
        <p>今回の取引相手はどうでしたか？</p>

        {{-- 星評価 --}}
        <div class="stars">
            <img class="star" data-value="1" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="2" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="3" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="4" src="{{ asset('images/modal_star_empty.png') }}">
            <img class="star" data-value="5" src="{{ asset('images/modal_star_empty.png') }}">
        </div>

        <form id="ratingForm" method="POST" action="{{ route('transactions.rate', $transaction->id) }}">
            @csrf
            <input type="hidden" id="ratingValue" name="rating" value="0">
            <button type="submit" class="submit-button">送信する</button>
        </form>
    </div>
</div>
