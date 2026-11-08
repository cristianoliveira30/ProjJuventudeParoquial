<?php

use App\Models\RetreatRegistration;

it('keeps health fields encrypted in the database and readable through the model', function () {
    $reg = new RetreatRegistration(['allergies' => 'amendoim']);

    $raw = $reg->getAttributes()['allergies'];

    expect($raw)->not->toBe('amendoim')
        ->and($reg->allergies)->toBe('amendoim');
});
