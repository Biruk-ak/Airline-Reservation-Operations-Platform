<?php
namespace App\Services\TicketPricing;

use App\Models\TicketPricing\FareRule;
use Carbon\Carbon;

class FareEffectiveWindow
{
    public function isEffectiveForTravelDate(FareRule $rule, $travelDate): bool
    {
        $moment = Carbon::parse($travelDate);
        if ($rule->effective_from && $moment->lt($rule->effective_from)) {
            return false;
        }
        if ($rule->effective_to && $moment->gt($rule->effective_to)) {
            return false;
        }
        return (bool) $rule->is_active;
    }
}
