<?php

namespace App\Models;

use App\Http\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements Auditable, JWTSubject
{
    use CreatedUpdatedBy, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'role_id',
        'token',
        'name',
        'mobile',
        'device_id',
        'subcription_start',
        'subcription_end',
        'email',
        'email_verified_at',
        'password',
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'email_verified_at' => 'timestamp',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)
            ->select('id', 'name');
    }

    public static function getUserDataUsingMobile($mobile)
    {
        return self::where('mobile', $mobile)->first();
    }

    public static function updateUser($request)
    {

        return self::find($request->user->id)
            ->update([
                'name' => trim(ucwords($request->name)),
                'mobile' => $request->mobile,
                'email' => $request->email,
                'address_line_1' => $request->address_line_1 ?? null,
                'address_line_1' => $request->address_line_1 ?? null,
                'state' => $request->state ?? null,
                'city' => $request->city ?? null,
                'pincode' => $request->pincode ?? null,
            ]);
    }

    public static function getUserDetails($user_id)
    {
        $user = User::select(
            'id',
            'name',
            'mobile',
        )
            ->where('id', $user_id)
            ->first();

        return $user;
    }
}
