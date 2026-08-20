<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;

it('logs the user out, invalidates the session and returns to the parish portal', function () {
    Auth::shouldReceive('logout')->once();

    $this->withoutMiddleware(Authenticate::class)
        ->post(route('logout'))
        ->assertRedirect(route('portal.home'));
});
