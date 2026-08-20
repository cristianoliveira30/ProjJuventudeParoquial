<?php

namespace App\Services;

use Stevebauman\Location\Facades\Location as FacadesLocation;
use Torann\GeoIP\Location;
use Torann\GeoIP\Services\AbstractService;

class GeoIpService extends AbstractService
{
    /**
     * Get the location information for the given IP address.
     *
     * @param  string  $ip
     * @return \Torann\GeoIP\Location
     */
    public function locate($ip)
    {
        $pos = FacadesLocation::get($ip);

        // Se não localizar (IP privado, localhost, etc), devolve "vazio"
        if (!$pos) {
            return new Location([
                'ip'      => $ip,
                'default' => true,
            ]);
        }

        return new Location([
            'ip'          => $ip,

            'iso_code'    => $pos->countryCode ?? null,
            'country'     => $pos->countryName ?? null,

            'state'       => $pos->regionCode ?? null,
            'state_name'  => $pos->regionName ?? null,

            'city'        => $pos->cityName ?? null,
            'postal_code' => $pos->zipCode ?? null,

            'lat'         => is_numeric($pos->latitude ?? null) ? (float) $pos->latitude : null,
            'lon'         => is_numeric($pos->longitude ?? null) ? (float) $pos->longitude : null,

            'timezone'    => $pos->timezone ?? null,

            'currency'    => $pos->currencyCode ?? null,

            'default'     => false,
        ]);
    }
}
