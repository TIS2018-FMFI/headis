<?php

namespace App\Mail;

use App\Challenge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDate extends Mailable
{
    use Queueable, SerializesModels;

    public $challenge;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Challenge $challenge)
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
            ->subject(trans('mails.CreateDateSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $this->challenge->asked->user_name]),
                "introLines" => [
                    trans('mails.CreateDateText')
                ],
                "actionText" => trans('mails.CurrentChallenge'),
                "actionUrl" =>  url(config('app.url').'/challenges/'.$this->challenge->id),
                "outroLines" => [

                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
