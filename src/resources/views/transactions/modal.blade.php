{{-- モーダルのレイアウト --}}
<div id="evaluationModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3>取引が完了しました。</h3>
        <p>今回の取引相手はどうでしたか？</p>
        <div class="star-rating">
            <img src="{{ asset('images/star_empty.png') }}" class="star" data-value="1">
            <img src="{{ asset('images/star_empty.png') }}" class="star" data-value="2">
            <img src="{{ asset('images/star_empty.png') }}" class="star" data-value="3">
            <img src="{{ asset('images/star_empty.png') }}" class="star" data-value="4">
            <img src="{{ asset('images/star_empty.png') }}" class="star" data-value="5">
        </div>
        <button id="submitRating" class="submit-button">送信する</button>
    </div>
</div>
