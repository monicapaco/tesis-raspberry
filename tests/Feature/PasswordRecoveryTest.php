<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

it('sends the recovery notification to a registered email', function () {
    Notification::fake();
    $user = User::factory()->create();

    $response = $this->post(route('password.email'), ['email' => $user->email]);

    $response->assertSessionHas('status', trans('passwords.sent'));
    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('reports an email that is not registered', function () {
    Notification::fake();

    $response = $this->from(route('password.request'))->post(route('password.email'), [
        'email' => 'no-registrado@example.com',
    ]);

    $response->assertRedirect(route('password.request'));
    $response->assertSessionHasErrors(['email' => trans('passwords.user')]);
    Notification::assertNothingSent();
});

it('builds an absolute recovery URL for the email', function () {
    $user = User::factory()->make(['name' => 'Ana', 'email' => 'ana@example.com']);
    $message = (new ResetPasswordNotification('test-token'))->toMail($user);

    expect($message->actionUrl)->toBe(route('password.reset', [
        'token' => 'test-token',
        'email' => 'ana@example.com',
    ]));
});

it('changes the password once and leaves the user logged out', function () {
    Notification::fake();
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
        'remember_token' => 'old-remember-token',
    ]);
    $token = null;

    $this->post(route('password.email'), ['email' => $user->email]);
    Notification::assertSentTo(
        $user,
        ResetPasswordNotification::class,
        function (ResetPasswordNotification $notification) use (&$token) {
            $token = $notification->token;

            return true;
        }
    );

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('password.reset.success'));
    $this->assertGuest();
    $user->refresh();
    expect(Hash::check('new-password', $user->password))->toBeTrue()
        ->and($user->remember_token)->not->toBe('old-remember-token');
    $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

    $secondResponse = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'another-password',
        'password_confirmation' => 'another-password',
    ]);

    $secondResponse->assertSessionHasErrors(['email' => trans('passwords.token')]);
    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});
