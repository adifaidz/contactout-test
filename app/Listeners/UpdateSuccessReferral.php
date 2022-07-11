<?php

namespace App\Listeners;

use App\Notifications\ReferralSuccessEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class UpdateSuccessReferral
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user->referer) {
            $event->user->referer->updateReferralPoint();
            Notification::send($event->user->referer, new ReferralSuccessEmail($event->user));
        }
    }
}
