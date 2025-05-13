<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransactionCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;

    /**
     * Create a new message instance.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // 商品名をメールの件名に動的に設定
        $subject = $this->transaction->item->name . 'の取引が完了しました';

        return $this->subject($subject)
                    ->markdown('emails.transaction_complete')
                    ->with([
                        'transaction' => $this->transaction,
                        'buyer' => $this->transaction->buyer,
                        'item' => $this->transaction->item,
                    ]);
    }
}
