<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Mail\ActivateUser;
use App\Mail\DeactivateUser;
use App\Match;
use App\NotAvailableDate;
use App\Point;
use App\Season;
use App\User;
use Faker\Provider\File;
use foo\bar;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @param Season|null $season
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Season $season = null)
    {
        if (auth()->user()) {
            $canChallenge = auth()->user()->id !== $user->id && auth()->user()->currentMatch() == null &&
                $user->countOfChallengesAsAsked() < 3 && User::currentChallenge(auth()->user())== null &&
                $user->currentMatch() == null && User::currentChallenge($user) == null &&
                !$user->isRedactor && !auth()->user()->isRedactor && Season::current() != null &&
                auth()->user()->position > $user->position && auth()->user()->countOfChallengesAsChallenger() < 3 &&
                (floor(sqrt(auth()->user()->position - 1)) === floor(sqrt($user->position - 1)) ||
                    floor(sqrt(auth()->user()->position - 1)) - 1 === floor(sqrt($user->position - 1))) &&
                NotAvailableDate::isAvailableDate(null, false);
        } else {
            $canChallenge = false;
        }


        $season = $season ?: Season::current();

        $countOfWins = 0;
        $matches = [];

        if ($season) {
            $matches = $user->matches($season->id);

            foreach ($matches as $match) {
                if ($match->winner->id == $user->id) {
                    $countOfWins++;
                }
            }
        }

        return view('user.show', [
            'user' => $user,
            'matches' => $matches,
            'canChallenge' => $canChallenge,
            'declinedMatches' => Match::allDeclinedMatches(),
            'restChallenge' => 3-$user->countOfChallengesAsChallenger(),
            'season' => $season,
            'seasons' => Season::orderBy('date_to', 'desc')->get(),
            'countOfWins' => $countOfWins
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
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'user_name' => ['nullable', 'string', 'max:255', 'unique:users,user_name'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image','mimes:jpg,jpeg,png', 'max:2500'],
        ]);
        $fileName = $user->image;
        if (isset($request['image'])) {
            $file = $request['image'];
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $fileName);

        }
        DB::transaction(function () use ($user, $request, $fileName){
            if (!empty($request['user_name'])) {
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
            if (isset($request['image']) && file_exists('/images/'.$user->image)){
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
            return response()->json([
                'status' => 'can not destroy',
            ]);
        }
        $success = DB::transaction(function () use ($user){
            $oldPosition = $user->position;
            $user->position = 0;
            $user->save();
            $success = $user->delete();
            $users = User::where('position' , '>', $oldPosition)->get();
            foreach ($users as $user1){
                $user1->position--;
                $user1->save();
            }

            return $success;
        });
        if ($success){
            Mail::send(new DeactivateUser($user));
        }
        return response()->json([
            'status' => $success ? 'success' : 'danger',
            'canDeactivateUsers' => User::canDeactivate(),
            'canReactivateUsers' => User::canActivate()
        ]);
    }

    public function activate(Request $request){
        if (Season::current() == null){
            $maxPoint = 0;
            $points = [];
        } else {
            $maxPoint = Point::where('season_id', Season::current()->id)->max('point');
            $points = Point::groupby(DB::raw('MONTH(date)'))->get();
        }
        $user = DB::transaction(function () use ($request, $maxPoint, $points){
            $user = User::withTrashed()->find($request['user_id']);
            $user->restore();
            $user->position = User::max('position') + 1;
            $user->save();
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
        if ($user->deleted_at == null){
            Mail::send(new ActivateUser($user));
        }
        return response()->json([
            'status' => $user->deleted_at ? 'danger' : 'success',
            'canReactivateUsers' => User::canActivate(),
            'canDeactivateUsers' => User::canDeactivate()
        ]);
    }

    public function updatePosition(Request $request)
    {
        $userIDsString = $request['users'];
        try {
            DB::transaction(function () use ($userIDsString) {
                $userIDs = explode(',', $userIDsString);
                for ($i = 0; $i < count($userIDs); $i++) {
                    /** @var User $user */
                    $user = User::find($userIDs[$i]);
                    $user->position = $i + 1;
                    $user->save();
                }
            });
        } catch (\Exception $exception){
            return response()->json([
                'status' => 'failed',
                'error' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Test',
            'users' => User::where('isRedactor',0)->orderBy('position')->get(['id', 'user_name', 'position'])
        ]);
    }
}
