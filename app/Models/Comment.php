<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getComments(Int $movie_id)
    {
        return $this->with('user')->where('movie_id', $movie_id)->get();
    }

    public function commentStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->movie_id = $data['movie_id'];
        $this->text = $data['text'];
        $this->save();

        return;
    }
}
