@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2 col-12 text-center mb-4">
                <h1>Headis</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque semper justo sit amet massa blandit, eget lobortis arcu imperdiet. Mauris lacus lacus, mollis nec felis eu, facilisis fringilla nisi. Nulla quis tristique velit. Integer finibus diam ac ex posuere congue. Quisque ullamcorper convallis nunc ut malesuada. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam malesuada efficitur tristique. Donec eget nisi luctus velit vehicula varius at ac turpis. In rutrum interdum lectus aliquet egestas. Nulla efficitur ipsum id faucibus fringilla. Praesent scelerisque quis justo in sollicitudin. Suspendisse odio justo, facilisis eu porta nec, dictum ac dolor. </p>
                <p>Quisque scelerisque justo eu ante pharetra, ac fringilla massa blandit. Integer ullamcorper dui nec feugiat suscipit. Curabitur interdum sagittis est, ac aliquam magna rhoncus at. Nunc magna sapien, aliquet et pulvinar in, commodo vitae massa. Duis pharetra, nibh ut facilisis venenatis, libero neque tristique neque, eu congue nunc dolor in risus. Nam at ultrices magna. Phasellus ac nisl posuere, laoreet odio eget, vestibulum ipsum. Nullam ex risus, molestie tincidunt quam ut, consectetur sollicitudin risus. Mauris pharetra nisl sed lacus gravida, eu tristique elit vestibulum. Nullam a ullamcorper dui, et aliquam ex. Sed rutrum sollicitudin neque, id lobortis metus.</p>
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
        <div class="text-center">
            <a href="/posts" class="btn btn-primary">{{ __('posts.more') }}</a>
        </div>
    </div>
@endsection
