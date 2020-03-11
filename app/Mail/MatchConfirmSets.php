<?php

namespace App\Mail;

use App\Match;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MatchConfirmSets extends Mailable
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
        return $this
            ->to($this->match->challenge->challenger->email)
            ->subject(trans('mails.MatchConfirmSetsSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $this->match->challenge->challenger->user_name]),
                "introLines" => [
                    trans('mails.MatchConfirmSetsText')
                ],
                "actionText" => trans('mails.CurrentMatch'),
                "actionUrl" =>  url(config('app.url').'/matches/'.$this->match->id),
                "outroLines" => [
                    //
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
