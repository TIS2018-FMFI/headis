@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="offset-1 text-center col-sm-4">
                <h3> {{ $user->user_name }} </h3>
                <img class="img-fluid mb-4" src="{{ url('images/'.$user->image)}}"  alt="{{ $user->user_name }}">
                @if(\App\User::currentChallenge($user) && $currentAuthUser->id == $user->id)
                    <a class="btn btn-primary" href="/challenges/{{\App\User::currentChallenge($user)->id}}">Aktuálna výzva</a>
                @endif
                @if($currentAuthUser->id !== $user->id && $user->countOfChallengesAsAsked() < 3)
                    <a class="btn btn-secondary" href="/challenges/store">Vyzvať!</a>
                @endif

                <h5>Aktuálna pozícia: {{ $user->position }}.</h5>
                @if($currentAuthUser->isRedactor)
                    <p>
                        <strong>Meno: </strong>{{ $user->first_name }}<br>
                        <strong>Priezvisko: </strong>{{ $user->last_name }}<br>
                        <strong>e-mail: </strong>{{ $user->email }}<br>
                    </p>
                    @if($currentAuthUser->id !== $user->id)
                        @if($user->deleted_at !== null)
                                <a href="/users/{{$user->id}}/destroy" class="btn btn-primary">Aktivácia hráča!</a>
                        @else
                                <a href="/users/{{$user->id}}/destroy" class="btn btn-primary">Deaktivácia hráča!</a>
                        @endif
                    @endif
                @endif
            </div>
            <div class="row">
                <div class="offset-5 text-center col-sm-6">
                    <table class="table">
                        <tr>
                            <th>Súper</th>
                            <th>Dátum</th>
                            <th>Set1</th>
                            <th>Set2</th>
                            <th>Set3</th>
                            <th>Výherca</th>
                        </tr>
                        @foreach($matches as $match)
{{--                            {{dd($match->challenge->asked)}}--}}
                            <th><a href="/users/{{$match->challenge->first()->challenger->id}}">{{$match->challenge->first()->challenger->user_name}}</a></th>
                            <th>{{$match->date->date}}</th>
                            @foreach($match->sets as $set)
                                <th>{{$set->score_1}}:{{$set->score_2}}</th>
                            @endforeach
                            @if(sizeof($match->sets) == 2)
                                <th>0:0</th>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection