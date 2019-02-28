<?php

namespace App\Mail;

use App\Challenge;
use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateComment extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $challenge = $this->comment->challenge;

        $userTo = $challenge->asked->id == $this->comment->user->id ? $challenge->asked : $challenge->challenger;

        return $this
            ->to($userTo->email)
            ->subject(trans('mails.CreateCommentSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', ['username' => $userTo->user_name]),
                "introLines" => [
                    trans('mails.CreateCommentText')
                ],
                "actionText" => trans('mails.CurrentChallenge'),
                "actionUrl" =>  url(config('app.url').'/challenges/'.$challenge->id),
                "outroLines" => [],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
