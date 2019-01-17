@extends('layouts.app')

@section('content')
    <div class="container-fluid">
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
    <div class="container">
        <div class="row border-bottom mt-4">
            <div class="col-sm-2 border-bottom">
                {{ __('pyramids.Date of start season') }}
            </div>
            <div class="col-sm-2 border-bottom">
                Koniec sezóny
            </div>
            <div class="col-sm-2 border-bottom">
                Najlepší hráč
            </div>
        </div>
        @foreach($statistics as $statistic)
            <div class="row mt-2">
                <div class="col-sm-2">
                    {{ $statistic->season->date_from }}
                </div>
                <div class="col-sm-2">
                    {{ $statistic->season->date_to }}
                </div>
                <div class="col-sm-2">
                    <a href="/users/{{$statistic->user->id}}">{{ $statistic->user->user_name }}</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection