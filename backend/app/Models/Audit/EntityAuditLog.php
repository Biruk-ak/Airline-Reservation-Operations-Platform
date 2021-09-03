<?php
namespace App\Models\Audit;
use Illuminate\Database\Eloquent\Model;

class EntityAuditLog extends Model
{
    protected $fillable = [
        'entity_type', 'entity_id', 'action', 'user_id', 'airline_id',
        'payload', 'ip_address', 'user_agent',
    ];

    protected $casts = ['payload' => 'array'];
}
