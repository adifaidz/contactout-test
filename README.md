# README 
## Introduction
This project uses [Laravel 8](https://laravel.com/docs/8.x), [InertiaJS](https://inertiajs.com/), [React](https://reactjs.org/) and [Tailwind](https://tailwindcss.com/). All the auths related stuff had been scaffolded using L[aravel Breeze](https://laravel.com/docs/8.x/starter-kits#laravel-breeze).

For testing,  it mainly uses [Pest](https://pestphp.com/) (aside from the scaffolded Laravel Breeze tests) and [Laravel Dusk](https://laravel.com/docs/8.x/dusk#main-content) for e2e testing.

## First time setup
Once done pulling the repo :
- Run `composer install`
- Setup the environment variables in `.env` ( Most important database stuffs )
- Run `npm install && npm run dev`
- Run `php artisan queue:work` if queueing is enabled
- Finally run `php artisan serve` or any equivalent command to start your laravel server.

## How to test
To run test you can these commands:
```
// To run feature and unit tests
./vendor/bin/pest

// To run e2e tests ( Laravel Dusk )
php artisan pest:dusk
```

To configure testing environment, you can refer to this [Laravel Documentation](https://laravel.com/docs/8.x/testing#environment)

## Features

### Key features

####  Auth
- User can register and login
- User must verify email
- User can reset password

#### Referral
- User has referral code on register ( configurable length)
- User can invite multiple emails
- User cannot invite existing users
- User cannot reinvite already invited emails
- User can gain 1 point with each successful referral
- User has a limit of 10 point ( configurable limit)

### Events and Notifications
This app uses events and notifications . Here's a list of them:

#### Events
- Registered
    - SendEmailVerificationNotification 
    - UpdateSuccessReferral

#### Notifications
- VerifyEmail
- ReferralInvitation
- ReferralSuccessEmail
