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
            [
                'name'           => 'VIP',
                'capacity'       => 500,
                'price'          => 1500000,
                'color_code'     => '#E8FF00',
                'position_x'     => 50,
                'position_y'     => 20,
                'path_koordinat' => null,
                'label_x'        => 350,
                'label_y'        => 135,
            ],
            [
                'name'           => 'Festival',
                'capacity'       => 2000,
                'price'          => 500000,
                'color_code'     => '#B0A0F8',
                'position_x'     => 50,
                'position_y'     => 50,
                'path_koordinat' => null,
                'label_x'        => 350,
                'label_y'        => 265,
            ],
            [
                'name'           => 'Tribune Kiri',
                'capacity'       => 600,
                'price'          => 750000,
                'color_code'     => '#F26B9E',
                'position_x'     => 20,
                'position_y'     => 50,
                'path_koordinat' => null,
                'label_x'        => 112,
                'label_y'        => 300,
            ],
            [
                'name'           => 'Tribune Kanan',
                'capacity'       => 600,
                'price'          => 750000,
                'color_code'     => '#F26B9E',
                'position_x'     => 80,
                'position_y'     => 50,
                'path_koordinat' => null,
                'label_x'        => 588,
                'label_y'        => 300,
            ],
            [
                'name'           => 'Regular',
                'capacity'       => 1300,
                'price'          => 350000,
                'color_code'     => '#9E9E9E',
                'position_x'     => 50,
                'position_y'     => 80,
                'path_koordinat' => null,
                'label_x'        => 350,
                'label_y'        => 388,
            ],
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
