@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2 col-12 text-center mb-4">
                <h1>{{ __('posts.news') }}</h1>
            </div>
        </div>
        <div class="row mb-5">
            @foreach($posts as $post)
                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card text-center h-100 {{ $post->hidden ? ' hidden-post' : '' }}">
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
        <div class="row">
            <div class="col">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
