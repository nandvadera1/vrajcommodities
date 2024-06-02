<?php

namespace App\Models;

use App\Http\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audits extends Model
{
    use  HasFactory;

    protected $table = 'audits';

    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
    ];

    public static function getQueryFindAudit($user_id, $search)
    {
        $query = Audits::select('id', 'user_id', 'event', 'auditable_type', 'old_values', 'new_values', 'created_at')
            ->where(function ($query) use ($user_id) {
                if (! empty($user_id)) {
                    $query->where('user_id', $user_id);
                }
            })
            ->where(function ($query) use ($search) {
                if (@$search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%'.$search.'%')
                            ->orWhere('user_id', 'like', '%'.$search.'%')
                            ->orWhere('user_type', 'like', '%'.$search.'%')
                            ->orWhere('event', 'like', '%'.$search.'%');
                    });
                }
            });

        return $query;
    }
}
