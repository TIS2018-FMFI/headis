@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2 col-12 text-center mb-4">
                <h1>{{ __('posts.News') }}</h1>
            </div>
        </div>
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card text-center mb-4">
                        <img class="card-img-top" src="{{ $post->image }}" alt="{{ $post->title }}">

                        <div class="card-body">
                            <h5 class="card-title"><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
