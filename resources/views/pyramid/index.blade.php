@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row flex-nowrap flex-row justify-content-center">
            @foreach($users as $user)
                <div class="col-lg-2">
                    <div class="card"><a href="/users/{{$user->id}}">{{$user->position}}.<br> {{$user->user_name}}</a></div>
                </div>
                @if(floor(sqrt($user->position)) == ceil(sqrt($user->position)))
                    </div>
                    <div class="row flex-nowrap flex-row justify-content-center">
                @endif
            @endforeach
        </div>
    </div>
    </div>

@endsection