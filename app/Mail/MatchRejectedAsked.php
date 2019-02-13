<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MatchRejectedAsked extends Mailable
{
    use Queueable, SerializesModels;

    public $match;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($match)
    {
        $this->match = $match;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->match->challenge->asked->email)
            ->subject(trans('mails.MatchRejectSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $this->match->challenge->asked->user_name]),
                "introLines" => [
                    trans('mails.MatchRejectAskedText', [
                        'challenger' => $this->match->challenge->challenger->user_name
                    ])
                ],
                "outroLines" => [
                    //
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
