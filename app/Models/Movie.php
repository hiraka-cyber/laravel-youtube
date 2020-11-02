<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Movie extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text','image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getUserTimeLine(Int $user_id)
    {
        return $this->where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(50);
    }

    public function getMovieCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }

    // 詳細画面
    public function getMovie(Int $movie_id)
    {
        return $this->with('user')->where('id', $movie_id)->first();
    }

    // 一覧画面
    public function getTimeLines(Int $user_id, Array $subscribe_ids)
    {
        $subscribe_ids[] = $user_id;
        return $this->whereIn('user_id', $subscribe_ids)->orderBy('created_at', 'DESC')->paginate(50);
    }

    public function movieStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->text = $data['text'];
        $this->image = $data['file'];
        $this->save();

        return;
    }

    public function getEditmovie(Int $user_id, Int $movie_id)
    {
        return $this->where('user_id', $user_id)->where('id', $movie_id)->first();
    }

    public function movieUpdate(Int $movie_id, Array $data)
    {
        $this->id = $movie_id;
        $this->text = $data['text'];
        $this->update();

        return;
    }

    public function movieDestroy(Int $user_id, Int $movie_id)
    {
        return $this->where('user_id', $user_id)->where('id', $movie_id)->delete();
    }
}
