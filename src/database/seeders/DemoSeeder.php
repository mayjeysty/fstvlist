<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSection;
use App\Models\Venue;
use App\Models\VenueSection;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $venue = Venue::firstOrCreate(
            ['name' => 'Jakarta International Expo'],
            [
                'address'  => 'Jl. Benyamin Sueb, Kemayoran, Jakarta Pusat',
                'city'     => 'Jakarta',
                'capacity' => 3500,
            ]
        );

        $sections = [
            ['name' => 'VIP',      'capacity' => 500,  'price' => 1500000, 'color_code' => '#f59e0b', 'position_x' => 50,  'position_y' => 20],
            ['name' => 'Festival', 'capacity' => 2000, 'price' => 500000,  'color_code' => '#6366f1', 'position_x' => 50,  'position_y' => 50],
            ['name' => 'Tribune',  'capacity' => 1000, 'price' => 750000,  'color_code' => '#10b981', 'position_x' => 20,  'position_y' => 50],
        ];

        foreach ($sections as $s) {
            VenueSection::firstOrCreate(
                ['venue_id' => $venue->id, 'name' => $s['name']],
                array_merge($s, ['remaining_capacity' => $s['capacity'], 'venue_id' => $venue->id])
            );
        }

        $event = Event::firstOrCreate(
            ['name' => 'FSTVLIST 2026 — Pop Indonesia Night'],
            [
                'venue_id'      => $venue->id,
                'description'   => 'Konser pop Indonesia terbesar tahun 2026.',
                'start_time'    => '2026-08-15 19:00:00',
                'end_time'      => '2026-08-15 23:00:00',
                'is_active'     => true,
                'queue_enabled' => false,
                'sales_open'    => true,
            ]
        );

        $venueSections = VenueSection::where('venue_id', $venue->id)->get();
        foreach ($venueSections as $vs) {
            EventSection::firstOrCreate(
                ['event_id' => $event->id, 'venue_section_id' => $vs->id],
                [
                    'price'           => $vs->price,
                    'quota'            => $vs->capacity,
                    'remaining_quota' => $vs->capacity,
                    'sold_count'       => 0,
                ]
            );
        }
    }
}
