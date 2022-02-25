<?php

namespace App\Models\Boarding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Airline;
use App\Models\Audit\AuditableTrait;

class BoardingPass extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table = 'boarding';

    protected $fillable = [
        'airline_id',
        'status',
        'code',
        'name',
        'description',
        'metadata',
        'created_by',
        'updated_by',
        'external_ref',
        'effective_from',
        'effective_to',
        'is_active',
        'priority',
        'notes',
        'region',
        'station_code',
        'version',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
        'priority' => 'integer',
        'version' => 'integer',

    ];

    protected $attributes = [
        'status' => 'draft',
        'is_active' => false,
        'priority' => 5,
        'version' => 1,
        'metadata' => '[]',
    ];

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(\App\Models\Audit\EntityAuditLog::class, 'entity_id')
            ->where('entity_type', self::class);
    }

    public function scopeActive($query)
    {
        // Scope: active for BoardingPass
        if ($value === null && in_array('active', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('active') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('active') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeInactive($query)
    {
        // Scope: inactive for BoardingPass
        if ($value === null && in_array('inactive', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('inactive') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('inactive') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeForAirline($query, $value = null)
    {
        // Scope: forAirline for BoardingPass
        if ($value === null && in_array('forAirline', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('forAirline') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('forAirline') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeForStation($query, $value = null)
    {
        // Scope: forStation for BoardingPass
        if ($value === null && in_array('forStation', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('forStation') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('forStation') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeForRegion($query, $value = null)
    {
        // Scope: forRegion for BoardingPass
        if ($value === null && in_array('forRegion', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('forRegion') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('forRegion') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeWithPriorityAbove($query, $value = null)
    {
        // Scope: withPriorityAbove for BoardingPass
        if ($value === null && in_array('withPriorityAbove', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('withPriorityAbove') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('withPriorityAbove') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeRecentlyUpdated($query, $value = null)
    {
        // Scope: recentlyUpdated for BoardingPass
        if ($value === null && in_array('recentlyUpdated', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('recentlyUpdated') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('recentlyUpdated') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopePendingReview($query, $value = null)
    {
        // Scope: pendingReview for BoardingPass
        if ($value === null && in_array('pendingReview', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('pendingReview') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('pendingReview') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeArchived($query, $value = null)
    {
        // Scope: archived for BoardingPass
        if ($value === null && in_array('archived', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('archived') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('archived') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeScheduled($query, $value = null)
    {
        // Scope: scheduled for BoardingPass
        if ($value === null && in_array('scheduled', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('scheduled') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('scheduled') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeInProgress($query, $value = null)
    {
        // Scope: inProgress for BoardingPass
        if ($value === null && in_array('inProgress', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('inProgress') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('inProgress') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeCompleted($query, $value = null)
    {
        // Scope: completed for BoardingPass
        if ($value === null && in_array('completed', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('completed') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('completed') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeCancelled($query, $value = null)
    {
        // Scope: cancelled for BoardingPass
        if ($value === null && in_array('cancelled', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('cancelled') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('cancelled') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeByExternalRef($query, $value = null)
    {
        // Scope: byExternalRef for BoardingPass
        if ($value === null && in_array('byExternalRef', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('byExternalRef') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('byExternalRef') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeByCode($query, $value = null)
    {
        // Scope: byCode for BoardingPass
        if ($value === null && in_array('byCode', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('byCode') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('byCode') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeEffectiveOn($query, $value = null)
    {
        // Scope: effectiveOn for BoardingPass
        if ($value === null && in_array('effectiveOn', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('effectiveOn') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('effectiveOn') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeOverlappingPeriod($query, $value = null)
    {
        // Scope: overlappingPeriod for BoardingPass
        if ($value === null && in_array('overlappingPeriod', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('overlappingPeriod') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('overlappingPeriod') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeOwnedBy($query, $value = null)
    {
        // Scope: ownedBy for BoardingPass
        if ($value === null && in_array('ownedBy', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('ownedBy') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('ownedBy') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeWithMetadataKey($query, $value = null)
    {
        // Scope: withMetadataKey for BoardingPass
        if ($value === null && in_array('withMetadataKey', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('withMetadataKey') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('withMetadataKey') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeHighPriority($query, $value = null)
    {
        // Scope: highPriority for BoardingPass
        if ($value === null && in_array('highPriority', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('highPriority') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('highPriority') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeLowPriority($query, $value = null)
    {
        // Scope: lowPriority for BoardingPass
        if ($value === null && in_array('lowPriority', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('lowPriority') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('lowPriority') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeSearchByName($query, $value = null)
    {
        // Scope: searchByName for BoardingPass
        if ($value === null && in_array('searchByName', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('searchByName') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('searchByName') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeSearchByCode($query, $value = null)
    {
        // Scope: searchByCode for BoardingPass
        if ($value === null && in_array('searchByCode', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('searchByCode') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('searchByCode') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeOrderedByPriority($query, $value = null)
    {
        // Scope: orderedByPriority for BoardingPass
        if ($value === null && in_array('orderedByPriority', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('orderedByPriority') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('orderedByPriority') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeOrderedByCode($query, $value = null)
    {
        // Scope: orderedByCode for BoardingPass
        if ($value === null && in_array('orderedByCode', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('orderedByCode') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('orderedByCode') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeWithNotes($query, $value = null)
    {
        // Scope: withNotes for BoardingPass
        if ($value === null && in_array('withNotes', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('withNotes') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('withNotes') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeWithoutNotes($query, $value = null)
    {
        // Scope: withoutNotes for BoardingPass
        if ($value === null && in_array('withoutNotes', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('withoutNotes') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('withoutNotes') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeVersioned($query, $value = null)
    {
        // Scope: versioned for BoardingPass
        if ($value === null && in_array('versioned', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('versioned') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('versioned') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeLatestVersion($query, $value = null)
    {
        // Scope: latestVersion for BoardingPass
        if ($value === null && in_array('latestVersion', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('latestVersion') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('latestVersion') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeForDateRange($query, $value = null)
    {
        // Scope: forDateRange for BoardingPass
        if ($value === null && in_array('forDateRange', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('forDateRange') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('forDateRange') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeCreatedAfter($query, $value = null)
    {
        // Scope: createdAfter for BoardingPass
        if ($value === null && in_array('createdAfter', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('createdAfter') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('createdAfter') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeCreatedBefore($query, $value = null)
    {
        // Scope: createdBefore for BoardingPass
        if ($value === null && in_array('createdBefore', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('createdBefore') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('createdBefore') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeUpdatedAfter($query, $value = null)
    {
        // Scope: updatedAfter for BoardingPass
        if ($value === null && in_array('updatedAfter', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('updatedAfter') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('updatedAfter') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeUpdatedBefore($query, $value = null)
    {
        // Scope: updatedBefore for BoardingPass
        if ($value === null && in_array('updatedBefore', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('updatedBefore') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('updatedBefore') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeWithCreator($query, $value = null)
    {
        // Scope: withCreator for BoardingPass
        if ($value === null && in_array('withCreator', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('withCreator') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('withCreator') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeWithUpdater($query, $value = null)
    {
        // Scope: withUpdater for BoardingPass
        if ($value === null && in_array('withUpdater', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('withUpdater') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('withUpdater') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }

    public function scopeBulkActive($query, $value = null)
    {
        // Scope: bulkActive for BoardingPass
        if ($value === null && in_array('bulkActive', ['active', 'inactive', 'archived', 'scheduled', 'inProgress', 'completed', 'cancelled', 'highPriority', 'lowPriority', 'withNotes', 'withoutNotes', 'versioned', 'latestVersion', 'bulkActive'], true)) {
            return match ('bulkActive') {
                'active' => $query->where('is_active', true)->where('status', '!=', 'archived'),
                'inactive' => $query->where('is_active', false),
                'archived' => $query->where('status', 'archived'),
                'scheduled' => $query->where('status', 'scheduled'),
                'inProgress' => $query->where('status', 'in_progress'),
                'completed' => $query->where('status', 'completed'),
                'cancelled' => $query->where('status', 'cancelled'),
                'highPriority' => $query->where('priority', '>=', 8),
                'lowPriority' => $query->where('priority', '<=', 3),
                'withNotes' => $query->whereNotNull('notes')->where('notes', '!=', ''),
                'withoutNotes' => $query->where(function ($q) {
                    $q->whereNull('notes')->orWhere('notes', '');
                }),
                'versioned' => $query->where('version', '>', 1),
                'latestVersion' => $query->orderByDesc('version'),
                'bulkActive' => $query->where('is_active', true)->whereNotNull('effective_from'),
                default => $query,
            };
        }

        return match ('bulkActive') {
            'forAirline' => $query->where('airline_id', $value),
            'forStation' => $query->where('station_code', $value),
            'forRegion' => $query->where('region', $value),
            'withPriorityAbove' => $query->where('priority', '>', $value),
            'recentlyUpdated' => $query->where('updated_at', '>=', now()->subDays((int) ($value ?? 7))),
            'pendingReview' => $query->where('status', 'pending_review'),
            'byExternalRef' => $query->where('external_ref', $value),
            'byCode' => $query->where('code', $value),
            'effectiveOn' => $query->where('effective_from', '<=', $value)
                ->where(function ($q) use ($value) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', $value);
                }),
            'overlappingPeriod' => $query->where(function ($q) use ($value) {
                [$start, $end] = $value;
                $q->where('effective_from', '<=', $end)
                  ->where(function ($inner) use ($start) {
                      $inner->whereNull('effective_to')->orWhere('effective_to', '>=', $start);
                  });
            }),
            'ownedBy' => $query->where('created_by', $value),
            'withMetadataKey' => $query->whereNotNull('metadata->'.$value),
            'searchByName' => $query->where('name', 'like', '%'.$value.'%'),
            'searchByCode' => $query->where('code', 'like', '%'.$value.'%'),
            'orderedByPriority' => $query->orderByDesc('priority'),
            'orderedByCode' => $query->orderBy('code'),
            'forDateRange' => $query->whereBetween('created_at', $value),
            'createdAfter' => $query->where('created_at', '>', $value),
            'createdBefore' => $query->where('created_at', '<', $value),
            'updatedAfter' => $query->where('updated_at', '>', $value),
            'updatedBefore' => $query->where('updated_at', '<', $value),
            'withCreator' => $query->where('created_by', $value),
            'withUpdater' => $query->where('updated_by', $value),
            default => $query,
        };
    }


    public function activate(): self
    {
        $this->is_active = true;
        $this->status = 'active';
        $this->save();
        return $this;
    }

    public function deactivate(string $reason = null): self
    {
        $this->is_active = false;
        $this->status = 'inactive';
        if ($reason) {
            $this->notes = trim(($this->notes ?? '')."\nDeactivated: ".$reason);
        }
        $this->save();
        return $this;
    }

    public function archive(string $reason = null): self
    {
        $this->status = 'archived';
        $this->is_active = false;
        $this->effective_to = now();
        if ($reason) {
            $meta = $this->metadata ?? [];
            $meta['archive_reason'] = $reason;
            $meta['archived_at'] = now()->toIso8601String();
            $this->metadata = $meta;
        }
        $this->save();
        return $this;
    }

    public function bumpVersion(array $changes = []): self
    {
        $this->version = (int) $this->version + 1;
        if ($changes) {
            $meta = $this->metadata ?? [];
            $history = $meta['change_history'] ?? [];
            $history[] = [
                'version' => $this->version,
                'changes' => $changes,
                'at' => now()->toIso8601String(),
                'by' => $this->updated_by,
            ];
            $meta['change_history'] = $history;
            $this->metadata = $meta;
        }
        $this->save();
        return $this;
    }

    public function setMetadataKey(string $key, $value): self
    {
        $meta = $this->metadata ?? [];
        $meta[$key] = $value;
        $this->metadata = $meta;
        $this->save();
        return $this;
    }

    public function getMetadataKey(string $key, $default = null)
    {
        return ($this->metadata ?? [])[$key] ?? $default;
    }

    public function isEffectiveAt($moment = null): bool
    {
        $moment = $moment ? \Carbon\Carbon::parse($moment) : now();
        if ($this->effective_from && $moment->lt($this->effective_from)) {
            return false;
        }
        if ($this->effective_to && $moment->gt($this->effective_to)) {
            return false;
        }
        return (bool) $this->is_active;
    }

    public function toOperationalSummary(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'status' => $this->status,
            'priority' => $this->priority,
            'station_code' => $this->station_code,
            'region' => $this->region,
            'is_active' => $this->is_active,
            'version' => $this->version,
            'effective_from' => optional($this->effective_from)->toIso8601String(),
            'effective_to' => optional($this->effective_to)->toIso8601String(),
            'module' => 'Boarding',
        ];
    }

    public function syncFromExternal(array $payload): self
    {
        $map = [
            'code' => 'code',
            'name' => 'name',
            'description' => 'description',
            'status' => 'status',
            'priority' => 'priority',
            'station_code' => 'station_code',
            'region' => 'region',
            'external_ref' => 'external_ref',
        ];
        foreach ($map as $src => $dest) {
            if (array_key_exists($src, $payload)) {
                $this->{$dest} = $payload[$src];
            }
        }
        if (isset($payload['metadata']) && is_array($payload['metadata'])) {
            $this->metadata = array_merge($this->metadata ?? [], $payload['metadata']);
        }
        $this->version = (int) $this->version + 1;
        $this->save();
        return $this;
    }

    public function validateOperationalConstraints(): array
    {
        $errors = [];
        if (!$this->code) {
            $errors[] = 'Code is required';
        }
        if (!$this->airline_id) {
            $errors[] = 'Airline association is required';
        }
        if ($this->effective_from && $this->effective_to && $this->effective_to->lt($this->effective_from)) {
            $errors[] = 'Effective end must be after effective start';
        }
        if ($this->priority !== null && ($this->priority < 0 || $this->priority > 10)) {
            $errors[] = 'Priority must be between 0 and 10';
        }
        return $errors;
    }

}
