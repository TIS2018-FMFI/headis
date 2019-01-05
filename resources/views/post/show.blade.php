@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>{{ $post->title }}</h1>
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
                <div class="row mb-5">
                    <div class="col">
                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="img-fluid w-100">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div>
                            {!! $post->text !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
