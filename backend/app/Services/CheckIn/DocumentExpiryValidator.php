<?php
namespace App\Services\CheckIn;

use App\Models\CheckIn\CheckIn;
use Carbon\Carbon;

class DocumentExpiryValidator
{
    public function validate(CheckIn $checkIn): array
    {
        $expiry = $checkIn->getMetadataKey('document_expires_on');
        if (!$expiry) {
            return ['ok' => true, 'reason' => null];
        }

        $expiresOn = Carbon::parse($expiry)->endOfDay();
        if ($expiresOn->lt(now())) {
            return [
                'ok' => false,
                'reason' => 'Travel document expired on '.$expiresOn->toDateString(),
            ];
        }

        return ['ok' => true, 'reason' => null];
    }
}
