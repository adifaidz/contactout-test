<?php

namespace App\Http\Controllers;

use App\Http\Requests\Referral\InviteRequest;
use App\Mail\ReferralInvitation;
use App\Models\UserReferralInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReferralController extends Controller
{
    public function index() {
        return Inertia::render('Referrals', [
            'referralCode' => Auth::user()->referral_code,
            'referralPoint' => Auth::user()->referral_point,
            'invites' => Auth::user()->referralInvites,
            'referees' => Auth::user()->referees,
        ]);
    }

    public function invite(InviteRequest $request) {
        foreach ($request->emails as $recipient) {
            UserReferralInvite::create([
                'email' => $recipient,
                'user_id' => Auth::user()->id,
            ]);

            Mail::to($recipient)->send(new ReferralInvitation(Auth::user()));
        }

        return redirect()->route('referrals');
    }
}
