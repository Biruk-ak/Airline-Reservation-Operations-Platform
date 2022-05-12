<?php
namespace App\Services\Cargo;

use App\Models\Cargo\CargoShipment;

class DangerousGoodsWarning
{
    public function warningsFor(CargoShipment $shipment): array
    {
        $isDg = (bool) $shipment->getMetadataKey('dangerous_goods', false);
        if (!$isDg) {
            return [];
        }

        $un = $shipment->getMetadataKey('un_number');
        if ($un) {
            return [];
        }

        return [[
            'code' => 'DG_MISSING_UN_NUMBER',
            'message' => 'Dangerous goods shipment is missing required UN number metadata',
        ]];
    }
}
