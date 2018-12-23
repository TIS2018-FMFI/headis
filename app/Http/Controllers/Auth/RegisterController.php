<?php

namespace App\Http\Controllers\Auth;

use App\Point;
use App\Season;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'image' => ['image','mimes:jpg,jpeg,png']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $fileName = 'default.png';
        if (isset($data['image'])) {
            $file = $data['image'];
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $fileName);
        }
        $currentSeason = Season::current();
        $maxPoint = Point::where('season_id', $currentSeason->id)->max('point');
        $points = Point::groupby(DB::raw('MONTH(date)'))->get();

        $user = DB::transaction(function () use ($data, $fileName, $maxPoint, $points) {
            $user = User::create([
                'user_name' => $data['user_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'image' => $fileName,
                'position' => User::max('position') + 1
            ]);

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

        return $user;
    }
}
