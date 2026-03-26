<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteBlock;
use App\Services\GeocodeService;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function update(Request $request, string $key)
    {
        $block = SiteBlock::where('key', $key)->firstOrFail();

        $data = $request->except(['_token', '_method', 'image', 'location_images_new']);

        $content = $block->content ?? [];

        if ($request->has('is_visible')) {
            $block->is_visible = $request->boolean('is_visible');
        }

        foreach ($data as $field => $value) {
            if ($field === 'title') {
                $block->title = $value;
            } elseif (in_array($field, ['is_visible'], true)) {
                continue;
            } elseif ($field === 'colors' && is_array($value)) {
                $content['colors'] = array_values($value);
            } elseif ($field === 'events' && is_array($value)) {
                $content['events'] = array_values(array_filter($value, fn($e) => is_array($e)));
            } elseif ($field === 'venues' && is_array($value)) {
                $content['venues'] = array_values(array_filter(
                    $value,
                    fn($e) => is_array($e) && $this->venueRowHasAnyValue($e)
                ));
            } elseif ($field === 'form_options' && is_array($value)) {
                $content['form_options'] = $this->normalizeFormOptions($value);
            } elseif ($field === 'question_labels' && is_array($value)) {
                $content['question_labels'] = array_map(
                    fn($v) => is_string($v) ? $v : (string) $v,
                    $value
                );
            } elseif ($field === 'location_images_existing' && is_array($value)) {
                $content['location_images'] = array_values(array_filter(
                    $value,
                    fn($path) => is_string($path) && $path !== '' && str_starts_with($path, 'uploads/')
                ));
            } else {
                $content[$field] = $value;
            }
        }

        if ($key === 'location' && !$request->has('venues')) {
            $content['venues'] = [];
        }

        if ($request->hasFile('location_images_new')) {
            $images = $content['location_images'] ?? [];
            foreach ($request->file('location_images_new') as $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }
                $ext = strtolower($file->getClientOriginalExtension());
                $filename = uniqid('loc_', true) . '.' . $ext;
                $uploadDir = public_path('uploads');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $file->move($uploadDir, $filename);
                $images[] = 'uploads/' . $filename;
            }
            $content['location_images'] = $images;
        }

        if ($key === 'location') {
            $content = $this->maybeGeocodeLocation($content, $request, app(GeocodeService::class));
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = uniqid('img_', true) . '.' . $ext;

            $uploadDir = public_path('uploads');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $file->move($uploadDir, $filename);
            $block->image_path = 'uploads/' . $filename;
        }

        $block->content = $content;
        $block->save();

        return response()->json(['success' => true, 'block' => $block]);
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array{attending: array<int, array{value: string, label: string}>, food: array<int, array{value: string, label: string}>, alcohol: array<int, array{value: string, label: string}>}
     */
    private function normalizeFormOptions(array $raw): array
    {
        $norm = static function (array $rows): array {
            $out = [];
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $value = isset($row['value']) ? trim((string) $row['value']) : '';
                $label = isset($row['label']) ? trim((string) $row['label']) : '';
                if ($value === '' || $label === '') {
                    continue;
                }
                $out[] = ['value' => $value, 'label' => $label];
            }

            return $out;
        };

        return [
            'attending' => $norm($raw['attending'] ?? []),
            'food'      => $norm($raw['food'] ?? []),
            'alcohol'   => $norm($raw['alcohol'] ?? []),
        ];
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    private function maybeGeocodeLocation(array $content, Request $request, GeocodeService $geocode): array
    {
        $latIn = $request->input('map_lat');
        $lngIn = $request->input('map_lng');
        if ($latIn !== null && $latIn !== '' && $lngIn !== null && $lngIn !== '') {
            $lat = filter_var($latIn, FILTER_VALIDATE_FLOAT);
            $lng = filter_var($lngIn, FILTER_VALIDATE_FLOAT);
            if ($lat !== false && $lng !== false) {
                $content['map_lat'] = round($lat, 6);
                $content['map_lng'] = round($lng, 6);
            }

            return $content;
        }

        $address = isset($content['map_address']) ? trim((string) $content['map_address']) : '';
        if ($address === '') {
            return $content;
        }

        $coords = $geocode->resolve($address);
        if ($coords) {
            $content['map_lat'] = $coords['lat'];
            $content['map_lng'] = $coords['lng'];
        }

        return $content;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function venueRowHasAnyValue(array $row): bool
    {
        foreach (['time', 'name', 'address', 'map_link'] as $k) {
            $v = isset($row[$k]) ? trim((string) $row[$k]) : '';
            if ($v !== '' && $v !== '#') {
                return true;
            }
        }

        return false;
    }

}
