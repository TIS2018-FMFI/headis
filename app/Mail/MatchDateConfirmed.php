<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;

class MatchDateConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $match;
    public $date;
    public $time;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($match)
    {
        $this->match = $match;
        $input  = $this->match->date->date;
        $this->date = Carbon::parse($input)->format('d.m.Y');
        $this->time = Carbon::parse($input)->format('H:i');
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
            ->subject(trans('mails.MatchDateConfirmedSubject'))
            ->markdown('vendor.notifications.email')->with([
                "level" => "default",
                "greeting" => trans('mails.Hello', [
                        'username' => $this->match->challenge->asked->user_name]),
                "introLines" => [
                    trans('mails.MatchDateConfirmedText', [
                        'challenger' => $this->match->challenge->challenger->user_name,
                        'date' => $this->date,
                        'time' => $this->time,
                    ]),
                ],
                "actionText" => trans('mails.CurrentMatch'),
                "actionUrl" =>  url('matches/'.$this->match->id),
                "outroLines" => [
                    //
                ],
                "salutation" => trans('mails.Goodbye')
            ]);
    }
}
