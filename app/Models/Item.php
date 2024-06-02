<?php

namespace App\Models;

use App\Http\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class Item extends Model implements Auditable
{
    use CreatedUpdatedBy, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'items';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'type',
        'category_id',
        'message',
        'image',
        'pdf',
        'excel',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getQueryForList($data)
    {
        $sql = Item::with('category');

        if (@$data['search']) {
            $sql->where(function ($query) use ($data) {
                $query->orWhere('message', 'like', '%' . $data['search'] . '%');
            });
        }
        if (@$data['category_id']) {
            $sql->where('category_id', $data['category_id']);
        }

        return $sql;
    }
}
