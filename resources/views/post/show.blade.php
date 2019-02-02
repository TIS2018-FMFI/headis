@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col"><h1>{{ $post->title }}</h1></div>
                    @if ($currentAuthUser && $currentAuthUser->isRedactor)
                        <div class="col text-right" ><a href="/posts/{{ $post->id }}/edit" class="btn btn-primary">{{ __('posts.editBtn') }}</a></div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <p>{{ $post->intro_text }}</p>
                    </div>
                    <div class="col text-right">
                        <span>{{ \Carbon\Carbon::parse($post->created_at)->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-12">
                @if ($post->image)
                    <div class="row mb-5">
                        <div class="col">
                            <img src="{{ url('images/'.$post->image)}}" alt="{{ $post->title }}" class="img-fluid w-100">
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col">
                        <div>
                            {!! html_entity_decode($post->text) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
