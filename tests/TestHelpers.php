<?php

namespace Tests;

use App\Country;
use App\Profession;
use Illuminate\Support\Str;

trait TestHelpers
{
    protected function assertDatabaseEmpty($table, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();

        $this->assertSame(0, $total, sprintf(
            "Failed asserting the table [%s] is empty. %s %s found.",
            $table,
            $total,
            Str::plural('row', $total)
        ));
    }

    protected function assertDatabaseCount($table, $expected, $connection = null)
    {
        $found = $this->getConnection($connection)->table($table)->count();
        $this->assertSame($expected, $found, sprintf(
            "Failed asserting the table [%s] has %s %s. %s %s found.",
            $table,
            $expected,
            Str::plural('row', $found),
            $found,
            Str::plural('row', $found)
        ));
    }

    public function withData(array $custom = [])
    {
        return array_merge($this->withOtherData(), $custom);
    }

    protected function defaultData()
    {
        return $this->defaultData;
    }

    protected function withOtherData()
    {
        $country = Country::factory()->create();

        return array_merge($this->defaultData(), [
            'country_id' => $country->id,
        ]);
    }
}
