<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * 新しいメッセージインスタンスを作成する。
     *
     * @param Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * メッセージエンベロープを取得する。
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【aivick_shop】注文内容のご確認',
        );
    }

    /**
     * メッセージ内容を取得する。
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'orders.mail',
        );
    }
}
