<?php

namespace Tests;

use App\Models\Event;
use App\Models\Venue;
use App\Models\VenueSection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function createRoles(): void
    {
        foreach (['super_admin', 'admin', 'customer', 'validator'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    protected function createVenueWithSections(array $sectionOverrides = []): Venue
    {
        $venue = Venue::create([
            'name'    => 'Test Venue',
            'address' => 'Test Address',
        ]);

        $defaults = [
            ['name' => 'VIP',      'capacity' => 500,  'price' => 1500000, 'color_code' => '#f59e0b', 'position_x' => 50,  'position_y' => 20],
            ['name' => 'Festival', 'capacity' => 2000, 'price' => 500000,  'color_code' => '#6366f1', 'position_x' => 50,  'position_y' => 50],
            ['name' => 'Tribune',  'capacity' => 1000, 'price' => 750000,  'color_code' => '#10b981', 'position_x' => 20,  'position_y' => 50],
        ];

        foreach ($defaults as $s) {
            $data = array_merge($s, ['venue_id' => $venue->id, 'remaining_capacity' => $s['capacity']]);
            if (isset($sectionOverrides[$s['name']])) {
                $data = array_merge($data, $sectionOverrides[$s['name']]);
            }
            VenueSection::create($data);
        }

        return $venue;
    }

    protected function createEvent(Venue $venue, array $overrides = []): Event
    {
        return Event::create(array_merge([
            'venue_id'      => $venue->id,
            'name'          => 'Test Concert',
            'description'   => 'A test concert event.',
            'start_time'    => now()->addDays(30),
            'end_time'      => now()->addDays(30)->addHours(4),
            'is_active'     => true,
            'queue_enabled' => false,
        ], $overrides));
    }
}
