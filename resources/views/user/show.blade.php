@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center col-sm-4">
                <h3> {{ $user->user_name }} </h3>
                <img class="img-fluid mb-4" src="{{ url('images/'.$user->image)}}"  alt="{{ $user->user_name }}">
                @if(\App\User::currentChallenge($user) && $currentAuthUser->id == $user->id)
                    <a class="btn btn-primary" href="/challenges/{{\App\User::currentChallenge($user)->id}}">Aktuálna výzva</a>
                @endif
                @if($currentAuthUser->id == $user->id)
                    <a class="btn btn-primary" href="/users/{{$user->id}}/edit">Upraviť profil</a>
                @endif

                @if($canChallenge)
                    <form action="/challenges/store" method="POST" >
                        @csrf
                        <input type="hidden" name="user" value="{{ $user->id }}">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success form-control {{ $errors->has('user') ? ' is-invalid' : '' }}">Vyzvať!</button>
                            @if($errors->has('user'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('user') }}</strong>
                                </span>
                            @endif
                        </div>
                    </form>
                @else
                    @if($user->id != $currentAuthUser->id)
                        <button disabled class="btn btn-danger">Nemôžes vyzvať hráča!</button>
                    @endif
                @endif

                <h5>Aktuálna pozícia: {{ $user->position }}.</h5>
                @if($currentAuthUser->isRedactor)
                    <p>
                        <strong>{{ __('users.First name') }}: </strong>{{ $user->first_name }}<br>
                        <strong>{{ __('users.Last name') }}: </strong>{{ $user->last_name }}<br>
                        <strong>e-mail: </strong>{{ $user->email }}<br>
                    </p>
                @endif
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="offset-1 text-center col-sm-11">
                        <div class="row">
                            <div class="col-4 font-weight-bold">{{ __('users.Opponent') }}</div>
                            <div class="col-8">
                                <div class="row mb-4">
                                    <div class="col-4 font-weight-bold">{{ __('users.Challenge date') }}</div>
                                    <div class="col-2 font-weight-bold">{{ __('users.Set') }}1</div>
                                    <div class="col-2 font-weight-bold">{{ __('users.Set') }}2</div>
                                    <div class="col-2 font-weight-bold">{{ __('users.Set') }}3</div>
                                    <div class="col-2 font-weight-bold">{{ __('users.Winner') }}</div>
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
                                    <div class="row">
                                        <div class="col-4">{{date('d-m-Y', strtotime($match->date->date))}}</div>
                                        @foreach($match->sets as $set)
                                            @if($user->id == $match->challenge->challenger->id)
                                                <div class="col-2">{{$set->score_1}}:{{$set->score_2}}</div>
                                            @else
                                                <div class="col-2">{{$set->score_2}}:{{$set->score_1}}</div>
                                            @endif
                                        @endforeach
                                        @if(sizeof($match->sets) == 2)
                                            <div class="col-2">0:0</div>
                                        @endif
                                        @if($user->id == $match->winner->id)
                                            <div class="col-2">✓</div>
                                        @else
                                            <div class="col-2">✗</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection