<?php
namespace App\Mail;


use App\Match;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestCancelMatchAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $match;

    /**
     * Create a new message instance.
     * @param User $user
     * @param Match $match
     * @param string $text
     */
    public function __construct(User $user, Match $match)
    {
        $this->user = $user;
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
            ->to($this->user->email)
            ->subject(trans('mails.RequestCancelMatchAccepted'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $this->user->user_name]),
                "introLines" => [
                    trans('mails.CancelMatchAcceptedText', ['date' => Carbon::parse($this->match->date->date)->format('d.m.Y')]),
                ],
                "outroLines" => [
                    //                "A line after the big button"
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }

}
