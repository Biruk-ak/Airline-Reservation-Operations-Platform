<?php
namespace App\Models\Audit;

trait AuditableTrait
{
    public static function bootAuditableTrait(): void
    {
        static::created(function ($model) {
            // Hook reserved for observers; AuditLogger is primary path.
        });
    }

    public function getAuditableAttributes(): array
    {
        return $this->getAttributes();
    }
}
