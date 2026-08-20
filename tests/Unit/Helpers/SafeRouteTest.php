<?php

it('returns the route url when the named route exists', function () {
    expect(safe_route('login', [], false))->toBe('/login');
});

it('returns the project fallback route when the named route does not exist', function () {
    expect(safe_route('testing.missing-route', [], false))
        ->toBe(route('#'));
});
