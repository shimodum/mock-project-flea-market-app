{{-- 取引チャット画面のサイドバー --}}
<div class="sidebar">
    <h3>その他の取引</h3>
    <ul class="transaction-list">
        @foreach ($sidebarTransactions as $sidebarTransaction)
            <li class="{{ $transaction->id == $sidebarTransaction->id ? 'active' : '' }}">
                <a href="{{ route('transactions.show', ['id' => $sidebarTransaction->id]) }}">
                    {{ $sidebarTransaction->item->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
