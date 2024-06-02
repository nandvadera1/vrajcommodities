<?php

namespace App\Models;

use App\Http\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class UserLogin extends Model implements Auditable
{
    use CreatedUpdatedBy, HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'user_login';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'mobile_unique_id',
        'push_notification_id',
        'device_type',
        'device_name',
        'carrier_name',
        'os_version',
        'app_version',
        'device_country',
        'activity',
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function addUserLoginData($data)
    {
        if (!isset($data['push_notification_id']) && empty($data['push_notification_id'])) {
            $data['push_notification_id'] = null;
        }
        if (!isset($data['device_type']) && empty($data['device_type'])) {
            $data['device_type'] = null;
        }
        if (!isset($data['device_name']) && empty($data['device_name'])) {
            $data['device_name'] = null;
        }
        if (!isset($data['carrier_name']) && empty($data['carrier_name'])) {
            $data['carrier_name'] = null;
        }
        if (!isset($data['os_version']) && empty($data['os_version'])) {
            $data['os_version'] = null;
        }
        if (!isset($data['app_version']) && empty($data['app_version'])) {
            $data['app_version'] = null;
        }
        if (!isset($data['device_country']) && empty($data['device_country'])) {
            $data['device_country'] = null;
        }
        if (!isset($data['activity']) && empty($data['activity'])) {
            $data['activity'] = null;
        }
        if (!isset($data['status']) && empty($data['status'])) {
            $data['status'] = null;
        }

        $userlogindata = UserLogin::where('push_notification_id', $data['push_notification_id'])->first();
        if (!empty($userlogindata)) {
            $userlogindata->user_id = $data['user_id'];
            $userlogindata->save();
        } else {
            $userLogin = new UserLogin();
            
            UserLogin::create([
                'user_id' => $data['user_id'],
                'mobile_unique_id' => null,
                'push_notification_id' => $data['push_notification_id'],
                'device_type' => $data['device_type'],
                'device_name' => $data['device_name'],
                'carrier_name' => $data['carrier_name'],
                'os_version' => $data['os_version'],
                'app_version' => $data['app_version'],
                'device_country' => $data['device_country'],
                'activity' => $data['activity'],
            ]);
        }
    }
}
