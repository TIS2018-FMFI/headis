@extends('layouts.app', ['seasons' => $seasons])

@section('content')
    <div class="container-fluid">
        <div class="text-center d-sm-none d-block"><h5>1. level</h5></div>
        <div class="row mb-4 text-center flex-sm-nowrap mx-0">
            @for ($i = $maxLevel; $i > 0; $i--)
                <div class="scrolling-wrapper-flexbox-empty mr-4 col d-none d-sm-block">&nbsp;</div>
            @endfor
            @php ($maxLevel--)
            @foreach ($users as $user)

                @if($currentAuthUser && $user->id == $currentAuthUser->id)
                    <div class="scrolling-wrapper-flexbox-item scrolling-wrapper-flexbox-item-current col mr-4 border pt-4" ><a class="text-white" href="/users/{{$user->id}}"><span class="d-sm-block">{{$user->position}}.</span>{{$user->user_name}}</a></div>
                @else
                    <div class="scrolling-wrapper-flexbox-item col mr-4 border pt-4"><a class="text-white" href="/users/{{$user->id}}"><span class="d-sm-block">{{$user->position}}.</span> {{$user->user_name}}</a></div>
                @endif
                @if(floor(sqrt($user->position)) == ceil(sqrt($user->position)))
                    </div>
                    <div class="text-center d-sm-none d-block"><h5>{{floor(sqrt($user->position))+1}}. level</h5></div>
                    <div class="row mb-4 text-center flex-sm-nowrap mx-0">
                        @for ($i = $maxLevel; $i > 0; $i--)
                            <div class="scrolling-wrapper-flexbox-empty mr-4 col d-none d-sm-block">&nbsp;</div>
                        @endfor
                        @php ($maxLevel--)
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