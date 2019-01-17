<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Match;
use App\User;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;
use function PHPSTORM_META\type;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $canChallenge = auth()->user()->id !== $user->id &&
                        $user->countOfChallengesAsAsked() < 3 &&
                        auth()->user()->currentMatch() == null &&
                        User::currentChallenge(auth()->user())== null &&
                        $user->currentMatch() == null &&
                        User::currentChallenge($user) == null;
        $matches = $user->matches();
        return view('user.show', [
            'user' => $user,
            'matches' => $matches,
            'canChallenge' => $canChallenge
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->currentMatch() != null || User::currentChallenge($user) != null){
            return back();
        }
        $oldPosition = $user->position;
        $user->position = 0;
        $user->save();
        $success = $user->delete();
        $users = User::where('position' , '>', $oldPosition)->get();
//        dd($users);
        foreach ($users as $user1){
            $user1->position--;
            $user1->save();
        }
        return back()->with([
            'success' => $success
        ]);
    }
}
