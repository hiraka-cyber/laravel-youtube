<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'screen_name',
        'name',
        'profile_image',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
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
        'email_verified_at' => 'datetime',
    ];

    public function subscribers()
    {
        return $this->belongsToMany(self::class, 'subscribers', 'subscribed_id', 'subscribing_id');
    }

    public function subscribes()
    {
        return $this->belongsToMany(self::class, 'subscribers', 'subscribing_id', 'subscribed_id');
    }

    public function getAllUsers(Int $user_id)
    {
        return $this->Where('id', '<>', $user_id)->paginate(15);
    }

    // フォローする
    public function subscribe(Int $user_id)
    {
        return $this->subscribes()->attach($user_id);
    }

    // フォロー解除する
    public function unsubscribe(Int $user_id)
    {
        return $this->subscribes()->detach($user_id);
    }

    // フォローしているか
    public function isSubscribing(Int $user_id)
    {
        return (boolean) $this->subscribes()->where('subscribed_id', $user_id)->first(['id']);
    }

    // フォローされているか
    public function isSubscribed(Int $user_id)
    {
        return (boolean) $this->subscribers()->where('subscribing_id', $user_id)->first(['id']);
    }

    public function updateProfile(Array $params)
    {
        if (isset($params['profile_image'])) {
            $file_name = $params['profile_image']->store('public/profile_image/');

            $this::where('id', $this->id)
                ->update([
                    'screen_name'   => $params['screen_name'],
                    'name'          => $params['name'],
                    'profile_image' => basename($file_name),
                    'email'         => $params['email'],
                ]);
        } else {
            $this::where('id', $this->id)
                ->update([
                    'screen_name'   => $params['screen_name'],
                    'name'          => $params['name'],
                    'email'         => $params['email'],
                ]);
        }

        return;
    }
}
