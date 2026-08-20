<?php

use function Pest\Laravel\get;

test('the parish portal is public', function () {
    get('/')
        ->assertOk()
        ->assertSee('Uma comunidade de fé, encontro e missão.');
});
