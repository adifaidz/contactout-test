<?php

use App\Models\User;
use App\Models\UserReferralInvite;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

it('automatically creates with referral code', function() {
    $user = User::factory()->create();

    expect($user->id)->not()->toBeEmpty();
    expect($user->referral_code)->not()->toBeEmpty();
});

it('has a referer', function() {
    $referer = User::factory()->create();

    $user = User::factory()
        ->count(1)
        ->for($referer, 'referer')
        ->create()->first();

    expect($user->referer)->not()->toBeEmpty();
    expect($referer->is($user->referer))->toBeTrue();
});

it('has many referees', function() {
    $referer = User::factory()->create();

    $users = User::factory()
        ->count(3)
        ->for($referer, 'referer')
        ->create();

    expect($referer->referees)->not()->toBeEmpty();
    expect($referer->referees->count())->toEqual(3);

    foreach ($users as $user) {
        expect($user->referer->is($referer))->toBeTrue();
    }
});

it('has many referral invites', function() {
    $user = User::factory()->create();

    $invites = UserReferralInvite::factory()
        ->count(3)
        ->for($user, 'user')
        ->create();

    expect($user->referralInvites)->not()->toBeEmpty();
    expect($user->referralInvites->count())->toEqual(3);

    foreach ($invites as $invite) {
        expect($invite->user->is($user))->toBeTrue();
    }
});

test('referralExists - uses existing referral code and return true', function () {
    $users = User::factory()->count(3)->create();

    $exists = User::referralExists($users->first()->referral_code);

    expect($exists)->toBeTrue();
});

test('referralExists - uses new referral code and return false', function () {
    User::factory()->count(3)->create();

    $exists = User::referralExists('new-non-existant-code');

    expect($exists)->toBeFalse();
});

test('generateReferralCode - generates a referral code', function () {
    $expectedCodeLength = config('referral.code_length');
    $referralCode = User::generateReferralCode();

    expect($referralCode)->toBeString()->and($referralCode)->toHaveLength($expectedCodeLength);
});

test('getReferralLink - get referral link', function () {
    $user = User::factory()->create();
    $expectedLink = route('register').'/?refer='.$user->referral_code;

    $referralLink = $user->getReferralLink();

    expect($referralLink)->toBeString()->and($referralLink)->toEqual($expectedLink);
});

test('sendEmailVerificationNotification - trigger VerifyEmail notification', function() {
    Notification::fake();

    $user = User::factory()->create();
    $user->sendEmailVerificationNotification();

    Notification::assertSentTo(
        [$user], VerifyEmail::class
    );
});

test('updateReferralPoint - should increment point successfully', function() {
    $user = User::factory()->create();
    $user->updateReferralPoint();

    $updatedUser = User::find($user->id);

    expect($updatedUser->referral_point)->tobe(1);
});

test('updateReferralPoint - will not increment when limit is met', function() {
    $pointLimit = config('referral.point_limit');
    $user = User::factory()->create();
    $user->referral_point = $pointLimit;
    $user->save();

    $user->updateReferralPoint();

    $updatedUser = User::find($user->id);

    expect($updatedUser->referral_point)->tobe($pointLimit);
});
