<?php
namespace App\Mail;


use App\Match;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestCancelMatch extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $match;
    public $text;

    /**
     * Create a new message instance.
     * @param User $user
     * @param Match $match
     * @param string $text
     */
    public function __construct(User $user, Match $match, string $text)
    {
        $this->user = $user;
        $this->match = $match;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $redactor = User::find(1); //redactor

        return $this
            ->to($redactor->email)
            ->subject(trans('mails.RequestCancelMatch'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => 'redaktor']),
                "introLines" => [
                    trans('mails.CancelMatchText', ['username' => $this->user->user_name, 'time' => '']),
                    trans('mails.CancelMatchPurpose'),
                    $this->text
                ],
                "actionText" => trans('mails.CancelMatch'),
                "actionUrl" =>  url(config('app.url').'/matches/'.$this->match->id),
                "outroLines" => [
                    //                "A line after the big button"
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
