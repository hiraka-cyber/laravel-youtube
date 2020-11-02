<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Movie;
use App\Models\Subscriber;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users'  => $all_users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Movie $movie, Subscriber $subscriber)
    {
        $login_user = auth()->user();
        $is_subscribing = $login_user->issubscribing($user->id);
        $is_subscribed = $login_user->issubscribed($user->id);
        $timelines = $movie->getUserTimeLine($user->id);
        $movie_count = $movie->getmovieCount($user->id);
        $subscribe_count = $subscriber->getsubscribeCount($user->id);
        $subscriber_count = $subscriber->getsubscriberCount($user->id);

        return view('users.show', [
            'user'           => $user,
            'is_subscribing'   => $is_subscribing,
            'is_subscribed'    => $is_subscribed,
            'timelines'      => $timelines,
            'movie_count'    => $movie_count,
            'subscribe_count'   => $subscribe_count,
            'subscriber_count' => $subscriber_count
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'screen_name'   => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'name'          => ['required', 'string', 'max:255'],
            'profile_image' => ['file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)]
        ]);
        $validator->validate();
        $user->updateProfile($data);

        return redirect('users/'.$user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function subscribe(User $user)
    {
        $subscriber = auth()->user();
        // フォローしているか
        $is_subscribing = $subscriber->isSubscribing($user->id);
        if(!$is_subscribing) {
            // フォローしていなければフォローする
            $subscriber->subscribe($user->id);
            return back();
        }
    }

    // フォロー解除
    public function unsubscribe(User $user)
    {
        $subscriber = auth()->user();
        // フォローしているか
        $is_subscribing = $subscriber->isSubscribing($user->id);
        if($is_subscribing) {
            // フォローしていればフォローを解除する
            $subscriber->unsubscribe($user->id);
            return back();
        }
    }
}
