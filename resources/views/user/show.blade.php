@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center col-md-4">
                <h3> {{ $user->user_name }} </h3>
                <img class="img-fluid mb-4" src="{{ url('images/'.$user->image)}}"  alt="{{ $user->user_name }}">

                @auth
                    @if(\App\User::currentChallenge($user) && $currentAuthUser->id == $user->id )
                        <a class="btn btn-primary" href="/challenges/{{\App\User::currentChallenge($user)->id}}">{{ __('users.Actual Challenge') }}</a>
                    @endif
                    @if($currentAuthUser->id == $user->id)
                        <a class="btn btn-primary" href="/users/{{$user->id}}/edit">{{__('users.Edit profile')}}</a>
                        @if (!$currentAuthUser->isRedactor)
                            <div>
                                {{ trans_choice('users.rest', $restChallenge, ['count' => $restChallenge]) }}
                            </div>
                        @endif
                    @endif

                        @if($canChallenge && !$currentAuthUser->isRedactor)
                            <form action="/challenges/store" method="POST" >
                                @csrf
                                <input type="hidden" name="user" value="{{ $user->id }}">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success form-control {{ $errors->has('user') ? ' is-invalid' : '' }}">{{ __('users.Challenge') }}</button>
                                    @if($errors->has('user'))
                                        <span class="invalid-feedback">
                                    <strong>{{ $errors->first('user') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </form>
                        @else
                            @if($user->id != $currentAuthUser->id && !$currentAuthUser->isRedactor)
                                <button disabled class="btn btn-danger">{{ __('users.You can not challenge the player') }}</button>
                            @endif
                        @endif
                        @if (!$user->isRedactor)
                            <h5>{{ __('users.Actual position') }}: {{ $user->position }}.</h5>
                        @endif
                        @if($currentAuthUser->isRedactor)
                            <p>
                                <strong>{{ __('users.First name') }}: </strong>{{ $user->first_name }}<br>
                                <strong>{{ __('users.Last name') }}: </strong>{{ $user->last_name }}<br>
                                <strong>e-mail: </strong>{{ $user->email }}<br>
                            </p>
                        @endif
                @endauth
                @if (!$user->isRedactor)
                    <h4 class="mt-5">{{ __('users.statistics') }}</h4>
                    <p>{{ __('users.count_of_won_matches', ['count' => $countOfWins]) }}</p>
                    <p>{{ __('users.count_of_all_matches', ['count' => count($matches)]) }}</p>
                @endif
            </div>
                @if($currentAuthUser && $currentAuthUser->isRedactor && $user->isRedactor)
                    <div class="col-md-8 pre-scrollable">
                        <div class="row">
                            <div class="text-center col-sm-11">
                                <div class="row">
                                    <div class="col-4 font-weight-bold">{{ __('users.Challenger') }}</div>
                                    <div class="col-4 font-weight-bold">{{ __('users.Opponent') }}</div>
                                    <div class="col-3 font-weight-bold">{{ __('users.Match') }}</div>
                                </div>
                                @foreach($declinedMatches as $match)
                                    <div class="row mt-4">
                                        <div class="col-4 font-weight-bold">
                                            <a href="/users/{{$match->challenge->challenger->id}}">{{$match->challenge->challenger->user_name}}</a>
                                        </div>
                                        <div class="col-4 font-weight-bold">
                                            <a href="/users/{{$match->challenge->asked->id}}">{{$match->challenge->asked->user_name}}</a>
                                        </div>
                                        <div class="col-3">
                                            <a class="btn btn-primary" href="/matches/{{$match->id}}">{{ __('users.Show more') }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                <div class="col-md-8 pre-scrollable">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3>Sezóna {{ $season->getCurrentLabel() }}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-12">
                            <div class="row">
                                <div class="col-4 font-weight-bold">{{ __('users.Opponent') }}</div>
                                <div class="col-8">
                                    <div class="row mb-4">
                                        <div class="col-4 font-weight-bold">{{ __('users.Challenge date') }}</div>
                                        <div class="col-2 font-weight-bold d-none d-sm-block">{{ __('users.Set') }}1</div>
                                        <div class="col-2 font-weight-bold d-none d-sm-block">{{ __('users.Set') }}2</div>
                                        <div class="col-2 font-weight-bold d-none d-sm-block">{{ __('users.Set') }}3</div>
                                        <div class="col-2 font-weight-bold">{{ __('users.Result') }}</div>
                                    </div>
                                </div>
                            </div>
                            @foreach($matches as $match)
                                <div class="row mb-4">
                                    <div class="col-4">
                                        @if($user->id == $match->challenge->challenger->id)
                                            <a href="/users/{{$match->challenge->asked->id}}">{{$match->challenge->asked->user_name}}</a>
                                        @else
                                            <a href="/users/{{$match->challenge->challenger->id}}">{{$match->challenge->challenger->user_name}}</a>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        @if($currentAuthUser && $currentAuthUser->isRedactor)
                                            <a href="/matches/{{ $match->id }}" class="text-decoration-none">
                                        @endif
                                            <div class="row">
                                                <div class="col-4">{{ \Carbon\Carbon::parse($match->date->date)->format('d.m.Y')}}</div>
                                                @foreach($match->sets as $set)
                                                    @if($user->id == $match->challenge->challenger->id)
                                                        <div class="col-2 d-none d-sm-block">{{$set->score_1}}:{{$set->score_2}}</div>
                                                    @else
                                                        <div class="col-2 d-none d-sm-block">{{$set->score_2}}:{{$set->score_1}}</div>
                                                    @endif
                                                @endforeach
                                                @if(sizeof($match->sets) == 2)
                                                    <div class="col-2 d-none d-sm-block">0:0</div>
                                                @endif
                                                <div class="col-2">{{ $match->result($user) }}</div>
                                            </div>
                                        @if($currentAuthUser && $currentAuthUser->isRedactor)
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
        </div>
    </div>

@endsection

@section('seasons')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,20">
            {{ __('Season') }}
        </a>
        <div class="dropdown-menu text-center pre-scrollable" aria-labelledby="navbarDropdown">
            @foreach($seasons as $s)
                @if (!$s->isCurrent())
                    <a class="nav-link" href="/users/{{ $user->id }}/season/{{ $s->id }}">{{ $s->getLabel() }}</a>
                @else
                    <a class="nav-link" href="/users/{{ $user->id }}">{{ __("Current") }}</a>
                @endif
            @endforeach
        </div>
    </li>
@endsection
