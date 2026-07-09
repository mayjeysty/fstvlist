<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class Zones extends Component
{
    public Event $event;
    public ?int $selectedZoneId = null;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function selectZone(int $sectionId): void
    {
        $this->selectedZoneId = $this->selectedZoneId === $sectionId ? null : $sectionId;
    }

    private function detectZoneType(string $name): string
    {
        $lower = strtolower($name);
        if (str_contains($lower, 'vip')) return 'vip';
        if (str_contains($lower, 'festival')) return 'festival';
        if (str_contains($lower, 'tribune') || str_contains($lower, 'tribun')) return 'tribune';
        return 'regular';
    }

    private function getBrandColor(string $zoneType, bool $soldOut): string
    {
        if ($soldOut) return '#9E9E9E';
        return match ($zoneType) {
            'vip'      => '#E8FF00',
            'festival' => '#B0A0F8',
            'tribune'  => '#F26B9E',
            default    => '#9E9E9E',
        };
    }

    private function getTextColor(string $zoneType, bool $soldOut): string
    {
        if ($soldOut) return '#FFFFFF';
        return match ($zoneType) {
            'vip' => '#332200',
            default => '#FFFFFF',
        };
    }

    private function computeCentroid(string $pathData): array
    {
        $cleaned = str_replace(['M', 'L', 'Q', 'Z', 'C', 'S', 'T', 'A', 'V', 'H'], ' ', $pathData);
        $cleaned = preg_replace('/\s+/', ' ', trim($cleaned));
        $rawTokens = explode(' ', $cleaned);
        $xs = [];
        $ys = [];

        for ($i = 0; $i < count($rawTokens); $i++) {
            $token = trim($rawTokens[$i]);
            if ($token === '') continue;

            if (str_contains($token, ',')) {
                $parts = explode(',', $token);
                if (count($parts) >= 2) {
                    $vx = (float)$parts[0];
                    $vy = (float)$parts[1];
                    if ($vx >= 0 && $vx <= 700 && $vy >= 0 && $vy <= 520) {
                        $xs[] = $vx;
                        $ys[] = $vy;
                    }
                }
            } elseif (is_numeric($token)) {
                $currentNum = (float)$token;
                if ($i + 1 < count($rawTokens) && is_numeric(trim($rawTokens[$i + 1]))) {
                    $nextNum = (float)trim($rawTokens[$i + 1]);
                    if ($currentNum >= 0 && $currentNum <= 700 && $nextNum >= 0 && $nextNum <= 520) {
                        $xs[] = $currentNum;
                        $ys[] = $nextNum;
                    }
                    $i++;
                }
            }
        }

        if (count($xs) === 0) return [350, 260];
        return [array_sum($xs) / count($xs), array_sum($ys) / count($ys)];
    }

    private function assignDefaultPaths(array &$zoneDataItems): void
    {
        $tribuneAssigned = 0;
        $regularAssigned = 0;

        $templatePaths = [
            'vip'             => 'M 240 90 L 460 90 L 440 180 L 260 180 Z',
            'festival'        => 'M 230 190 L 470 190 L 500 340 L 200 340 Z',
            'tribune_left'    => 'M 30 200 L 195 195 L 195 400 L 60 420 Z',
            'tribune_right'   => 'M 505 195 L 670 200 L 640 420 L 505 400 Z',
            'regular_center'  => 'M 195 345 L 505 345 L 470 430 L 230 430 Z',
        ];

        foreach ($zoneDataItems as &$zone) {
            $type = $zone['zoneType'];

            switch ($type) {
                case 'vip':
                    $zone['pathData'] = $templatePaths['vip'];
                    break;

                case 'festival':
                    $zone['pathData'] = $templatePaths['festival'];
                    break;

                case 'tribune':
                    if ($tribuneAssigned % 2 === 0) {
                        $zone['pathData'] = $templatePaths['tribune_left'];
                    } else {
                        $zone['pathData'] = $templatePaths['tribune_right'];
                    }
                    $tribuneAssigned++;
                    break;

                default:
                    if ($regularAssigned === 0) {
                        $zone['pathData'] = $templatePaths['regular_center'];
                    } else {
                        $slotIndex = $regularAssigned - 1;
                        $columnOffset = ($slotIndex % 2) * 200;
                        $rowOffset = intdiv($slotIndex, 2) * 60;

                        $x1 = 195 + $columnOffset;
                        $x2 = 505 + $columnOffset;
                        $y1 = 345 + $rowOffset;
                        $y2 = 430 + $rowOffset;

                        $zone['pathData'] = "M {$x1} {$y1} L {$x2} {$y1} L " . ($x2 - 35) . " {$y2} L " . ($x1 + 35) . " {$y2} Z";
                    }
                    $regularAssigned++;
                    break;
            }
        }
        unset($zone);
    }

    public function render()
    {
        $this->event->load('venue.sections', 'eventSections.venueSection');

        $eventSections = $this->event->eventSections;
        $useEventSections = $eventSections->isNotEmpty();
        $source = $useEventSections ? $eventSections : $this->event->venue->sections;

        $zoneDataItems = [];
        foreach ($source as $item) {
            if ($useEventSections) {
                $vs = $item->venueSection;
                $data = [
                    'id'        => $vs->id,
                    'name'      => $vs->name,
                    'quota'     => $item->quota,
                    'price'     => $item->price,
                    'remaining' => $item->remaining_quota,
                    'soldOut'   => $item->isSoldOut(),
                    'pathRaw'   => $vs->path_koordinat,
                    'labelX'    => $vs->label_x,
                    'labelY'    => $vs->label_y,
                ];
            } else {
                $data = [
                    'id'        => $item->id,
                    'name'      => $item->name,
                    'quota'     => $item->capacity,
                    'price'     => $item->price,
                    'remaining' => $item->remaining_capacity,
                    'soldOut'   => $item->isSoldOut(),
                    'pathRaw'   => $item->path_koordinat,
                    'labelX'    => $item->label_x,
                    'labelY'    => $item->label_y,
                ];
            }

            $data['zoneType'] = $this->detectZoneType($data['name']);
            $data['pathData'] = $data['pathRaw'];
            $zoneDataItems[] = $data;
        }

        $zonesNeedingPaths = array_filter($zoneDataItems, fn($z) => empty($z['pathData']));
        if (!empty($zonesNeedingPaths)) {
            $this->assignDefaultPaths($zoneDataItems);
        }

        foreach ($zoneDataItems as &$zone) {
            $zone['color'] = $this->getBrandColor($zone['zoneType'], $zone['soldOut']);
            $zone['textColor'] = $this->getTextColor($zone['zoneType'], $zone['soldOut']);

            if ($zone['labelX'] !== null && $zone['labelY'] !== null) {
                $zone['labelX'] = (float)$zone['labelX'];
                $zone['labelY'] = (float)$zone['labelY'];
            } else {
                [$cx, $cy] = $this->computeCentroid($zone['pathData']);
                $zone['labelX'] = $cx;
                $zone['labelY'] = $cy;
            }
        }
        unset($zone);

        $selectedZone = null;
        if ($this->selectedZoneId) {
            $selectedZone = collect($zoneDataItems)->firstWhere('id', $this->selectedZoneId);
        }

        return view('livewire.events.zones', [
            'event'        => $this->event,
            'zoneData'     => $zoneDataItems,
            'selectedZone' => $selectedZone,
        ])->layout('layouts.app', ['title' => $this->event->name . ' — Pilih Zona']);
    }
}
