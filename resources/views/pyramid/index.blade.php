@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="overflow-x: scroll">
        <div class="row mb-4 text-center justify-content-center border-bottom">
            @foreach($users as $user)
                @if($user->id == $currentAuthUser->id)
                    <div class="mr-4 border rounded-circle bg-secondary" style="width: 150px; height: 150px"><a class="text-success" href="/users/{{$user->id}}">{{$user->position}}.<br><br> {{$user->user_name}}</a></div>
                @else
                    <div class="mr-4 border rounded-circle bg-secondary" style="width: 150px; height: 150px"><a class="text-white" href="/users/{{$user->id}}">{{$user->position}}.<br><br> {{$user->user_name}}</a></div>
                @endif
                @if(floor(sqrt($user->position)) == ceil(sqrt($user->position)))
                    </div>
                    <div class="row mb-4 text-center justify-content-center border-bottom">
                @endif
            @endforeach
        </div>
    </div>

    <div class="container">
        <div class="row border-bottom mt-4">
            <div class="col-sm-2 border-bottom">
                {{ __('pyramids.Month') }}
            </div>
            <div class="col-sm-2 border-bottom">
                {{ __('pyramids.The best player') }}
            </div>
        </div>
        @foreach($actualStatistics as $statistic)
            <div class="row mt-2">
                <div class="col-sm-2">
                    {{ __('pyramids.'.date('F', strtotime($statistic->date )))}}
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
                {{ __('pyramids.Date of end season') }}
            </div>
            <div class="col-sm-2 border-bottom">
                {{ __('pyramids.The best player') }}
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