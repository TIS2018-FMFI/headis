@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2 col-12 text-center mb-4">
                <h1>Headis</h1>
            </div>
        </div>
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card text-center  h-100">
                        @if ($post->image)
                            <img class="card-img-top" src="{{ url('images/'.$post->image)}}" alt="{{ $post->title }}">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title mb-0"><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mb-4">
            <a href="/posts" class="btn btn-primary">{{ __('posts.more') }}</a>
        </div>
        <div class="row">
            <div class="col text-center">
                <h2>Najbližšie zápasy</h2>
            </div>
        </div>
        <div class="row">
            @foreach($currentMatches as $match)
                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title"><a href="{{ route('users.show', $match->challenge->challenger->id) }}">{{ $match->challenge->challenger->user_name }}</a> vs. <a href="{{ route('users.show', $match->challenge->asked->id) }}">{{ $match->challenge->asked->user_name }}</a></h5>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($match->date->date)->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            @foreach($currentChallenges as $challenge)
                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title"><a href="{{ route('users.show', $challenge->challenger->id) }}">{{ $challenge->challenger->user_name }}</a> vs. <a href="{{ route('users.show', $challenge->asked->id) }}">{{ $challenge->asked->user_name }}</a></h5>
                            <p class="mb-0">Termín nedohodnutý</p>
                        </div>
                    </div>
                </div>
            @endforeach
            @if((count($currentChallenges) + count($currentMatches)) === 0)
                <p>Aktuálne nie sú žiadne dohodnute zápasy</p>
            @endif
        </div>
    </div>
@endsection
