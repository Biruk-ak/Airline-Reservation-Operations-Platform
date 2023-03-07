<?php
namespace App\Services\AdminPortal;

class AuditSeverityClassifier
{
    public function classify(string $action): string
    {
        $action = strtolower($action);
        if (in_array($action, ['deleted', 'archive', 'deactivate', 'permission_revoked'], true)) {
            return 'high';
        }
        if (in_array($action, ['updated', 'sync', 'bulk'], true)) {
            return 'medium';
        }
        return 'low';
    }
}
