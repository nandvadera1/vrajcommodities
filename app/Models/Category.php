<?php

namespace App\Models;

use App\Http\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    use CreatedUpdatedBy, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'categories';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
    ];

    public static function getQueryForList($data)
    {
        $sql = Category::select('id', 'name')
            ->where('status', 'Active');

        if (@$data['search']) {
            $sql->where(function ($query) use ($data) {
                $query->orWhere('name', 'like', '%' . $data['search'] . '%');
            });
        }

        return $sql;
    }
}
