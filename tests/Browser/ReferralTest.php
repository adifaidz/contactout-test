<?php

use App\Models\User;
use App\Models\UserReferralInvite;
use Laravel\Dusk\Browser;

beforeEach(function() {
    $this->authUser = User::factory()->create([
        'email' => 'mock-user@test.com'
    ]);
});

it('has referral page and form', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->authUser)->visitRoute('referrals')
            ->assertSee('Referrals')
            ->assertInputPresent('emails')
            ->assertSeeIn('button[type=submit]', 'INVITE');
    });
});

it('submits successfully', function () {
    $this->browse(function(Browser $browser) {
        $browser->loginAs($this->authUser)->visitRoute('referrals')
        ->keys('.email-select input:not([type=hidden])', 'test@test.com', '{enter}')
        ->keys('.email-select input:not([type=hidden])', 'test2@test.com', '{enter}')
        ->keys('.email-select input:not([type=hidden])', 'test3@test.com', '{enter}')
        ->click('button[type=submit]')
        ->waitFor('button:not([disabled])[type=submit]')
        ->assertNotPresent('.text-red-600');
    });

    $userInvites = UserReferralInvite::all();
    expect($userInvites->count())->toBe(3);
    foreach ($userInvites as $userInvite) {
        expect($userInvite->user->is($this->authUser))->toBeTrue();
    }
});

it('has errors when invalid email address is entered', function () {
    $this->browse(function(Browser $browser) {
        $browser->loginAs($this->authUser)->visitRoute('referrals')
        ->keys('.email-select input:not([type=hidden])', 'invalid@value', '{enter}')
        ->assertSee('The input must be a valid email address.')
        ->keys('.email-select input:not([type=hidden])', 'invalid', '{tab}')
        ->assertSee('The input must be a valid email address.');
    });
});




