<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Match;
use App\Point;
use App\Season;
use App\User;
use Faker\Provider\File;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $canChallenge = auth()->user()->id !== $user->id && auth()->user()->currentMatch() == null &&
                        $user->countOfChallengesAsAsked() < 3 && User::currentChallenge(auth()->user())== null &&
                        $user->currentMatch() == null && User::currentChallenge($user) == null &&
                        !$user->isRedactor && !auth()->user()->isRedactor &&
                        auth()->user()->position > $user->position &&
                        (floor(sqrt(auth()->user()->position - 1)) === floor(sqrt($user->position - 1)) ||
                        floor(sqrt(auth()->user()->position - 1)) - 1 === floor(sqrt($user->position - 1)));

        $matches = $user->matches();
        return view('user.show', [
            'user' => $user,
            'matches' => $matches,
            'canChallenge' => $canChallenge
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (auth()->user()->id != $user->id){
            return back();
        }
        return view('user.edit', [
            'user' => $user
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
//        dd($request);
        $this->validate($request, [
            'user_name' => ['nullable', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image','mimes:jpg,jpeg,png'],
        ]);
        $fileName = 'default.png';
        if (isset($request['image'])) {
            $file = $request['image'];
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $fileName);

        }
        DB::transaction(function () use ($user, $request, $fileName){
            if (!empty($request['user_name'])) {
//                dd('som debil');
                $user->user_name = $request['user_name'];
            }
            if (!empty($request['email'])){
                $user->email = $request['email'];
            }
            if (!empty($request['first_name'])){
                $user->first_name = $request['first_name'];
            }
            if (!empty($request['last_name'])) {
                $user->last_name = $request['last_name'];
            }
            if (file_exists('/images/'.$user->image)){
                unlink('/images/'.$user->image);
            }
            $user->image = $fileName;
            if (!empty($request['password'])){
                $user->password = Hash::make($request['password']);
            }

            $user->save();
        });
        return redirect('/users/'.$user->id);
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
        $success = DB::transaction(function () use ($user){
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
            return $success;
        });
        return response()->json([
            'status' => $success ? 'success' : 'danger',
            'canDeactivateUsers' => User::canDeactivate()
        ]);
    }

    public function activate($user_id){
        $maxPoint = Point::where('season_id', Season::current()->id)->max('point');
        $points = Point::groupby(DB::raw('MONTH(date)'))->get();

        $user = DB::transaction(function () use ($user_id, $maxPoint, $points){
            $user = User::withTrashed()->find($user_id)->restore();
            $pointCount = 0;
            foreach ($points as $point) {
                $temp = Point::create([
                    'user_id' => $user->id,
                    'date' => $point->date,
                    'season_id' => $point->season_id,
                    'point' => $maxPoint + 1
                ]);
                if ($temp) $pointCount++;
            }

            if(!$user || $pointCount != count($points)) throw new \Exception('Something wrong');

            return $user;
        });
        return response()->json([
            'status' => $user->deleted_at ? 'danger' : 'success',
            'canActivateUsers' => User::canActivate(),
        ]);
    }
}
