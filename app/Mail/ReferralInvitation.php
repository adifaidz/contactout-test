<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $referer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->referer = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@contactout.com')
            ->subject(ucfirst($this->referer->name) . ' recommends ContactOut')
            ->markdown('mail.referrals.invite')
            ->with([
                'referralLink' => $this->referer->getReferralLink(),
                'name' => ucfirst($this->referer->name),
            ]);
    }
}
