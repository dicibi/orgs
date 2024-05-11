<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Models\Job;
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

        $exampleStructure = $organizer->structures()->create('Example');

        $financeDirectorTitle = $organizer->jobTitles()->create('Director of Finance', $exampleStructure); // make job title
        $centralOffice = $organizer->offices()->create('Central Office', $exampleStructure); // make office
        $openedFinanceDirectorPosition = $centralOffice->openPositionFor($financeDirectorTitle); // open position within office with selected job title

        $user->assignPosition($openedFinanceDirectorPosition); // assign user to opened position

        $this->assertEquals(1, $user->employments()->count());
        $this->assertEquals($user->active_position->position->id, $openedFinanceDirectorPosition->id);
        $this->assertEquals($user->active_position->position->title->name, $openedFinanceDirectorPosition->title->name);
        $this->assertEquals($user->active_position->position->office->name, $openedFinanceDirectorPosition->office->name);

        /** @var Job\Title $presidentDirectorTitle */
        $presidentDirectorTitle = $organizer->jobTitles()->create('President Director'); // make job title
        $openedPresidentDirectorPosition = $presidentDirectorTitle->openPositionIn($centralOffice); // assign president director to central office

        $user->assignPosition($openedPresidentDirectorPosition); // promote from Finance to President Director

        $this->assertEquals(2, $user->employments()->count());
        $this->assertEquals($user->active_position->position->id, $openedPresidentDirectorPosition->id);
        $this->assertEquals($user->active_position->position->title->name, $openedPresidentDirectorPosition->title->name);
        $this->assertEquals($user->active_position->position->office->name, $openedPresidentDirectorPosition->office->name);
    }
}