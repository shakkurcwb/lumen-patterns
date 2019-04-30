<?php

namespace App\Models;

use Carbon\Carbon;

class UserMetaModel extends Model
{
    protected $table = 'user_meta';

    public $timestamps = false;

    protected $attributes =
    [
        'locale' => 'en_CA',
        'gender' => '',
    ];

    protected $fillable =
    [
        'locale', 'theme', 'avatar',
        'gender', 'birth', 'nacionality', 
    ];

    protected $dates =
    [
        'birth',
    ];

    protected $hidden =
    [
        'id', 'user_id',
    ];

    protected $casts =
    [
        'birth' => 'date:Y-m-d',
    ];

    protected $appends =
    [
        'age',
    ];

    #
    # Mutators & Accessors
    #

    /**
     * Set the user's theme.
     *
     * @param  String  $value
     * @return Void
     */
    public function setThemeAttribute($value)
    {
        $this->attributes['theme'] = strtolower($value);
    }

    /**
     * Get the user's age.
     *
     * @return Carbon\Carbon
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth)->age;
    }
}
