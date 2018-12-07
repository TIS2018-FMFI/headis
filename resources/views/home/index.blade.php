@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Headis</h1>
                <p>lorem ipsum</p>
            </div>
        </div>
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card text-center mb-4">
                        <img class="card-img-top" src="{{ $post->image }}" alt="{{ $post->title }}">

                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ str_limit($post->text, $limit = 100, $end = '...') }}</p>
                        </div>
                        <a href="/posts/{{ $post->id }}" class="btn btn-primary">Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection