<?php

use App\Models\Retreat;
use App\Models\RetreatAttendanceRecord;
use App\Models\RetreatAttendanceSession;
use App\Models\RetreatMinistry;
use App\Models\RetreatRegistration;
use App\Models\RetreatServant;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

it('keeps participants servants ministries and attendance as separate retreat relations', function () {
    $retreat = new Retreat;

    expect($retreat->registrations())->toBeInstanceOf(HasMany::class)
        ->and($retreat->registrations()->getRelated())->toBeInstanceOf(RetreatRegistration::class)
        ->and($retreat->servants())->toBeInstanceOf(HasMany::class)
        ->and($retreat->servants()->getRelated())->toBeInstanceOf(RetreatServant::class)
        ->and($retreat->ministries()->getRelated())->toBeInstanceOf(RetreatMinistry::class)
        ->and($retreat->attendanceSessions()->getRelated())->toBeInstanceOf(RetreatAttendanceSession::class);
});

it('supports independent attendance records for participants and servants', function () {
    expect((new RetreatRegistration)->attendanceRecords())->toBeInstanceOf(MorphMany::class)
        ->and((new RetreatServant)->attendanceRecords())->toBeInstanceOf(MorphMany::class)
        ->and((new RetreatAttendanceSession)->records()->getRelated())->toBeInstanceOf(RetreatAttendanceRecord::class)
        ->and(array_keys(RetreatAttendanceSession::AUDIENCES))->toBe(['participants', 'servants'])
        ->and(array_keys(RetreatAttendanceSession::DIRECTIONS))->toBe(['departure', 'return']);
});
