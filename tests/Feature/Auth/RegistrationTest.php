<?php

use App\Providers\AppServiceProvider;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});
test('registration rejects short passwords with a thai minimum length message', function () {
    $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'short@example.com',
        'password' => 'Ab12!xy',
        'password_confirmation' => 'Ab12!xy',
    ])->assertSessionHasErrors(['password' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร']);
    $this->assertDatabaseMissing('users', ['email' => 'short@example.com']);
});

test('production registration accepts eight characters while keeping other password checks', function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
    $this->app->detectEnvironment(fn (): string => 'production');
    (new AppServiceProvider($this->app))->boot();
    $this->mock(UncompromisedVerifier::class)
        ->shouldReceive('verify')->once()->andReturnTrue();

    $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'eight@example.com',
        'password' => 'Abcd12!?',
        'password_confirmation' => 'Abcd12!?',
    ])->assertSessionHasNoErrors()->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticated();
});
