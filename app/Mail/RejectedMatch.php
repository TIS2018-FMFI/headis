<?php

namespace App\Mail;

use App\Match;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectedMatch extends Mailable
{
    use Queueable, SerializesModels;

    public $match;

    /**
     * Create a new message instance.
     *
     * @param Match $match
     */
    public function __construct(Match $match)
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
        $redactor = User::redactor();
        return $this
            ->to($redactor->email)
            ->subject(trans('mails.MatchRejectSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $redactor->user_name]),
                "introLines" => [
                    trans('mails.MatchRejectText', [
                        'challenger' => $this->match->challenge->challenger->user_name
                    ])
                ],
                "actionText" => trans('mails.EditMatch'),
                "actionUrl" =>  url('matches/'.$this->match->id),
                "outroLines" => [
                    //
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
