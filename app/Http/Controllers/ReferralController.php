<?php

namespace App\Http\Controllers;

use App\Mail\ReferralInvitation;
use App\Models\UserReferralInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ReferralController extends Controller
{
    public function index() {
        return Inertia::render('Referrals', [
            'referralCode' => Auth::user()->referral_code,
        ]);
    }

    public function invite(Request $request) {
        $this->validate($request, [
            'emails' => 'required|array',
            'emails.*' => [
                'string',
                'distinct',
                'email',
                'unique:users,email',
                Rule::unique('user_referral_invites', 'email')
                    ->where(fn ($query) => $query->where('user_id', Auth::user()->id))
            ],
        ]);

        // dd($request->emails);
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
