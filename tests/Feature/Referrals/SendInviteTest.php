<?php

use App\Mail\ReferralInvitation;
use App\Models\User;
use App\Models\UserReferralInvite;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

beforeEach(function() {
    $this->authUser = User::factory()->create([
        'email' => 'mock-user@test.com'
    ]);
});

it('has referrals page', function () {
    $response = $this->actingAs($this->authUser)->get(route('referrals'));

    $response->assertStatus(200);
});

it('can send email invites successfully', function () {
    Mail::fake();

    $emailAddresses = ['test@test.com', 'test2@test.com', 'test3@test.com'];

    $response = $this->actingAs($this->authUser)->post(route('referrals.invite'), [
        'emails' => $emailAddresses,
    ]);

    $userInvites = UserReferralInvite::all();

    expect($userInvites->count())->tobe(count($emailAddresses));
    Mail::assertQueued(ReferralInvitation::class, count($emailAddresses));

    $response->assertRedirect(route('referrals'));
});

it('fails inviting existing users', function () {
    Mail::fake();

    $emailAddresses = [
        'mock-other-user1@test.com',
        'mock-other-user2@test.com',
        'mock-other-user3@test.com'
    ];

    User::factory()
        ->count(3)
        ->state(new Sequence(...array_map(
            fn($email) => ['email' => $email],
            $emailAddresses
        )))
        ->create();

    $response = $this->actingAs($this->authUser)->post(route('referrals.invite'), [
        'emails' => $emailAddresses,
    ]);

    $expectedErrors = [];

    /**
     * To create expected errors in the expected format
     *
     * Example: [
     *  'emails.0' => [The emails.0 has already been taken.]
     * ]
     */
    foreach ($emailAddresses as $index => $email) {
        $expectedErrors['emails.'. $index] = [
            'The '. ('emails.'. $index) .' has already been taken.'
        ];
    }

    expect(User::all()->count())->toBe(4);
    $response->assertSessionHasErrors(array_keys($expectedErrors));
    $responseError = session('errors');
    expect($responseError->all())->toMatchArray(Arr::flatten($expectedErrors));

    Mail::assertNotQueued(ReferralInvitation::class);

});

it('fails when inviting already invited emails', function () {
    Mail::fake();

    $emailAddresses = ['test@test.com', 'test2@test.com', 'test3@test.com'];

    UserReferralInvite::factory()
        ->count(3)
        ->state(new Sequence(...array_map(
            fn($email) => ['email' => $email],
            $emailAddresses
        )))
        ->for($this->authUser, 'user')
        ->create();

    $response = $this->actingAs($this->authUser)->post(route('referrals.invite'), [
        'emails' => $emailAddresses,
    ]);

    $expectedErrors = [];

    /**
     * To create expected errors in the expected format
     *
     * Example: [
     *  'emails.0' => [The emails.0 has already been taken.]
     * ]
     */
    foreach ($emailAddresses as $index => $email) {
        $expectedErrors['emails.'. $index] = [
            'The '. ('emails.'. $index) .' has already been taken.'
        ];
    }

    expect(UserReferralInvite::all()->count())->toBe(3);
    $response->assertSessionHasErrors(array_keys($expectedErrors));
    $responseError = session('errors');
    expect($responseError->all())->toMatchArray(Arr::flatten($expectedErrors));

    Mail::assertNotQueued(ReferralInvitation::class);
});
