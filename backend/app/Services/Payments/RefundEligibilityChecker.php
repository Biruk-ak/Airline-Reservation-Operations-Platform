<?php
namespace App\Services\Payments;

use App\Models\Payments\Payment;

class RefundEligibilityChecker
{
    public function check(Payment $payment): array
    {
        $blocked = (bool) $payment->getMetadataKey('refund_blocked', false);
        $eligibleStatuses = ['completed', 'active', 'captured'];
        $eligible = !$blocked && in_array($payment->status, $eligibleStatuses, true);

        return [
            'refund_eligible' => $eligible,
            'reason' => $eligible ? null : ($blocked ? 'Refund blocked by metadata flag' : 'Payment status is not refundable'),
        ];
    }
}
