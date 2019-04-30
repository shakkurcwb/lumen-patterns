<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Crypt;

class UserModel extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    use SoftDeletes;

    protected $table = 'users';

    protected $fillable =
    [
        'first_name', 'last_name', 'email', 'password',
    ];

    protected $guarded =
    [
        'is_admin', 'api_token', 'ip_address',
        'last_seen_at', 'verified_at',
    ];

    protected $dates =
    [
        'verified_at', 'last_seen_at',
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $casts =
    [
        'is_admin' => 'boolean',
        'verified_at' => 'datetime:Y-m-d',
        'last_seen_at' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];

    protected $with =
    [
        'meta',
    ];

    protected $hidden =
    [
        'password', 'api_token', 'ip_address', 'is_admin',
    ];

    protected $appends =
    [
        'full_name', 'is_active', 'is_verified',
    ];

    public static function boot()
    {
        parent::boot();

        // Generate API Token when user is created
        self::creating(function($model) { $model->api_token = str_random(60); });
    }

    #
    # Mutators & Accessors
    #

    /**
     * Set the user's first name.
     *
     * @param  String  $value
     * @return Void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

    /**
     * Get the user's first name.
     *
     * @param  String  $value
     * @return String
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Set the user's last name.
     *
     * @param  String  $value
     * @return Void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtolower($value);
    }

    /**
     * Get the user's last name.
     *
     * @param  String  $value
     * @return String
     */
    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Get the user's full name.
     *
     * @return String
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Set the user's password (encrypt).
     *
     * @param  String  $value
     * @return Void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encrypt($value);
    }

    /**
     * Get the user's general status.
     *
     * @return Boolean
     */
    public function getIsActiveAttribute()
    {
        return empty($this->deleted_at) ? true : false;
    }

    /**
     * Get the user's email verification status.
     *
     * @return Boolean
     */
    public function getIsVerifiedAttribute()
    {
        return !empty($this->verified_at) ? true : false;
    }

    #
    # Relationships
    #

    /**
     * Get the meta record associated with the user.
     * 
     * @return App\Models\UserMetaModel
     */
    public function meta()
    {
        return $this->hasOne('App\Models\UserMetaModel', 'user_id', 'id');
    }
}
