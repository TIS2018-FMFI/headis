@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2 col-12 text-center mb-4">
                <h1>Novinky</h1>
            </div>
        </div>
        <div class="card-deck">
            @foreach($posts as $post)
                <div class="card text-center mb-4" style="flex: 0 0 30%;">
                    <img class="card-img-top" src="{{ $post->image }}" alt="{{ $post->title }}">

                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ str_limit($post->text, $limit = 100, $end = '...') }}</p>
                    </div>
                    <a href="/posts/{{ $post->id }}" class="btn btn-primary">Detail</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection