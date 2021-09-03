<?php
namespace App\Events\Operations;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleEventOccurred
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $module,
        public string $action,
        public array $payload,
    ) {}
}
