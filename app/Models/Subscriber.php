<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;
    protected $primaryKey = [
        'subscribing_id',
        'subscribed_id'
    ];
    protected $fillable = [
        'subscribing_id',
        'subscribed_id'
    ];
    public $timestamps = false;
    public $incrementing = false;

    public function getSubscribeCount($user_id)
    {
        return $this->where('subscribing_id', $user_id)->count();
    }

    public function getSubscriberCount($user_id)
    {
        return $this->where('subscribed_id', $user_id)->count();
    }

    public function subscribingIds(Int $user_id)
    {
        return $this->where('subscribing_id', $user_id)->get('subscribed_id');
    }
}
