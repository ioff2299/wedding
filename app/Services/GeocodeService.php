<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodeService
{
    /**
     * @return array{lat: float, lng: float}|null
     */
    public function resolve(string $address): ?array
    {
        $address = trim($address);
        if ($address === '') {
            return null;
        }

        $yandexKey = config('services.yandex_maps.api_key');
        if (is_string($yandexKey) && $yandexKey !== '') {
            $coords = $this->yandex($address, $yandexKey);
            if ($coords) {
                return $coords;
            }
        }

        return $this->nominatim($address);
    }

    /**
     * @return array{lat: float, lng: float}|null
     */
    private function yandex(string $address, string $apiKey): ?array
    {
        try {
            $response = Http::timeout(10)->get('https://geocode-maps.yandex.ru/1.x/', [
                'apikey'  => $apiKey,
                'geocode' => $address,
                'format'  => 'json',
                'results' => 1,
            ]);

            if (!$response->successful()) {
                return null;
            }

            $json = $response->json();
            $pos = data_get($json, 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos');
            if (!is_string($pos) || $pos === '') {
                return null;
            }

            $parts = preg_split('/\s+/', trim($pos));
            if ($parts === false || count($parts) < 2) {
                return null;
            }

            $lng = (float) $parts[0];
            $lat = (float) $parts[1];

            return [
                'lat' => round($lat, 6),
                'lng' => round($lng, 6),
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array{lat: float, lng: float}|null
     */
    private function nominatim(string $address): ?array
    {
        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'WeddingInvitation/1.0',
                    'Accept'     => 'application/json',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q'      => $address,
                    'format' => 'json',
                    'limit'  => 1,
                ]);

            if (!$response->successful()) {
                return null;
            }

            $json = $response->json();
            if (!is_array($json) || $json === [] || !isset($json[0]['lat'], $json[0]['lon'])) {
                return null;
            }

            return [
                'lat' => round((float) $json[0]['lat'], 6),
                'lng' => round((float) $json[0]['lon'], 6),
            ];
        } catch (\Throwable) {
            return null;
        }
    }
}
