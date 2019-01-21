@extends('layouts.app')

@section('content')
    <div class="container-fluid pre-scrollable">
        <div class="row flex-nowrap flex-row text-center justify-content-center">
            @foreach($users as $user)
                <div class="col-lg-2">
                    @if($user->id == $currentAuthUser->id)
                        <div class="card"><a class="text-success" href="/users/{{$user->id}}">{{$user->position}}.<br> {{$user->user_name}}</a></div>
                    @else
                        <div class="card"><a href="/users/{{$user->id}}">{{$user->position}}.<br> {{$user->user_name}}</a></div>
                    @endif
                </div>
                @if(floor(sqrt($user->position)) == ceil(sqrt($user->position)))
                    </div>
                    <div class="row flex-nowrap flex-row text-center justify-content-center">
                @endif
            @endforeach
        </div>
    </div>

    <div class="container">
        <div class="row border-bottom mt-4">
            <div class="col-sm-2 border-bottom">
                Mesiac
            </div>
            <div class="col-sm-2 border-bottom">
                Najlepší hráč
            </div>
        </div>
        @foreach($actualStatistics as $statistic)
            <div class="row mt-2">
                <div class="col-sm-2">
                    {{ date('F', strtotime($statistic->date ))}}
                </div>
                <div class="col-sm-2">
                    <a href="/users/{{$statistic->user->id}}">{{ $statistic->user->user_name }}</a>
                </div>
            </div>
        @endforeach
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