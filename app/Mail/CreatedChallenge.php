<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatedChallenge extends Mailable
{
    use Queueable, SerializesModels;

    public $challenge;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->challenge->asked->email)
            ->subject(trans('mails.ChallengeCreateSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $this->challenge->asked->user_name]),
                "introLines" => [
                    trans('mails.ChallengeCreateText', [
                        'asker' => $this->challenge->asked->user_name,
                        'challenger' => $this->challenge->challenger->user_name
                    ])
                ],
                "actionText" => trans('mails.CurrentChallenge'),
                "actionUrl" =>  url('challenges/'.$this->challenge->id),
                "outroLines" => [
                    trans('mails.ChallengeCreateOutText')
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
