<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ActivateUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
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
            ->subject(trans('mails.ActivateUserSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $this->user->user_name]),
                "introLines" => [
                    trans('mails.ActivateUserText')
                ],
                "actionText" => trans('mails.MyAccount'),
                "actionUrl" =>  url(config('app.url').'/users/'.$this->user->id),
                "outroLines" => [
    //                "A line after the big button"
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
