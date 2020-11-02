<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Movie;
use App\Models\Comment;
use App\Models\Subscriber;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Movie $movie, Subscriber $subscriber)
    {
        $user = auth()->user();
        $subscribe_ids = $subscriber->subscribingIds($user->id);
        // subscribed_idだけ抜き出す
        $subscribing_ids = $subscribe_ids->pluck('subscribed_id')->toArray();

        $timelines = $movie->getTimelines($user->id, $subscribing_ids);

        return view('movies.index', [
            'user'      => $user,
            'timelines' => $timelines
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        return view('movies.create', [
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Movie $movie)
    {
        $user = auth()->user();
        $data = $request->all();
        $validator = Validator::make($data, [
            'text' => ['required', 'string', 'max:140'],
            'file' => ['required','file','mimes:mov,avi,mp4,flv',]
        ]);

        if ($request->file('file')->isValid([])) {
            $path = $request->file->store('public/profile_image/');

            $file_name = basename($path);
            $user_id = auth()->user()->id;
            $text = $request->text;
            $new_image_data = new Movie();
            $new_image_data->user_id = $user_id;
            $new_image_data->image = $file_name;
            $new_image_data->text = $text;

            $new_image_data->save();

            return redirect('movies');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors();
        }

        $validator->validate();
        $movie->movieStore($user->id, $data);

        return redirect('movies');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie, Comment $comment, Subscriber $subscriber)
    {
        $user = auth()->user();
        $movie = $movie->getMovie($movie->id);
        $comments = $comment->getComments($movie->id);
        $subscribe_ids = $subscriber->subscribingIds($user->id);
        // subscribed_idだけ抜き出す
        $subscribing_ids = $subscribe_ids->pluck('subscribed_id')->toArray();

        $timelines = $movie->getTimelines($user->id, $subscribing_ids);

        return view('movies.show', [
            'user'     => $user,
            'timelines' => $timelines,
            'movie' => $movie,
            'comments' => $comments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        $user = auth()->user();
        $movies = $movie->getEditMovie($user->id, $movie->id);

        if (!isset($movies)) {
            return redirect('movies');
        }

        return view('movies.edit', [
            'user'   => $user,
            'movies' => $movies
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'text' => ['required', 'string', 'max:140']
        ]);
        $validator->validate();
        $movie->movieUpdate($movie->id, $data);

        return redirect('movies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $user = auth()->user();
        $movie->movieDestroy($user->id, $movie->id);

        return redirect('movies');
    }
}
