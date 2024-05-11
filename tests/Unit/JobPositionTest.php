<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Organizer;
use Dicibi\Orgs\Tests\Unit\Mock\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class JobPositionTest extends TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    function test_assign_positions()
    {
        $this->assertTrue(true);

        /** @var User $user */
        $user = User::query()->create();

        $this->assertEquals(0, $user->employments()->count());

        $organizer = app('orgs');

        assert($organizer instanceof Organizer);

        $financeDirectorTitle = $organizer->jobTitles()->create('Director of Finance'); // make job title
        $centralOffice = $organizer->offices()->create('Central Office'); // make office
        $openedFinanceDirectorPosition = $centralOffice->openPositionFor($financeDirectorTitle); // open position within office with selected job title

        $user->assignPosition($openedFinanceDirectorPosition); // assign user to opened position

        $this->assertEquals(1, $user->employments()->count());
        $this->assertEquals($user->active_position->position->id, $openedFinanceDirectorPosition->id);

        $presidentDirectorTitle = $organizer->jobTitles()->create('President Director'); // make job title
        $openedPresidentDirectorPosition = $centralOffice->openPositionFor($presidentDirectorTitle); // assign president director to central office

        $user->assignPosition($openedPresidentDirectorPosition); // promote from Finance to President Director

        $this->assertEquals(2, $user->employments()->count());
        $this->assertEquals($user->active_position->position->id, $openedPresidentDirectorPosition->id);
    }
}