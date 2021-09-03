<?php
namespace App\Services\Notifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Events\Operations\ModuleEventOccurred;

class OperationsNotifier
{
    public function notifyModuleEvent(string $module, string $action, array $payload): void
    {
        Log::channel('operations')->info("Module event", compact('module', 'action') + ['payload' => $payload]);
        try {
            Event::dispatch(new ModuleEventOccurred($module, $action, $payload));
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch module event', ['error' => $e->getMessage()]);
        }
    }

    public function notifyAlert(string $severity, string $title, array $context = []): void
    {
        Log::channel('operations')->log($severity === 'critical' ? 'critical' : 'warning', $title, $context);
    }
}
