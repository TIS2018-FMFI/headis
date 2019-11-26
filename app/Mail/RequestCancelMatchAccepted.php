<?php
namespace App\Mail;


use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestCancelMatchAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     * @param User $user
     * @param Match $match
     * @param string $text
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
                    trans('mails.CancelMatchAcceptedText'),
                ],
                "outroLines" => [
                    //                "A line after the big button"
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }

}
