@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2 col-12 text-center mb-4">
                <h1>Headis</h1>
                <p>Študent René Wegner (nie Wenger) s partiou si chcel na kúpalisku v Kaiserslauterne v roku 2006 zapinkať na ihrisku, no to bolo obsadené. Preto zobral loptu a s kamošom si išli zahlavičkovať na stole pre pingpong (pre koktavých stolný tenis). Vznikol tak Headis (head- ako hlava a -is ako tenis).<br>
                    Je to teda spojenie stolného tenisu a hlavičkovania. Hrá sa na dva víťazné sety do 11 bodov, pričom stôl je veľmi podobný tomu pingpongovému. Podanie sa strieda po 3 loptách a lopta sa môže odrážať jedine hlavou (žiadne ramená, krky či lopatky). Volej je povolený, ale pri ďalšom údere sa musí hráč dotknúť zeme (a teda nemôže ležať stále na stole). Lopta je guľatá, gumená a váži cca 100 gramov.<br>
                    Tento nový šport začal byť veľmi obľúbený najmä na univerzitách a postupne sa rozvinul po celom Nemecku. V roku 2008 sa konal prvý svetový pohár a v roku 2016 bolo až 11 medzinárodných pohárov s tisíckou účastníkov. Mimo Nemecka pôsobia oficiálny partneri napríklad vo Švajčiarsku, Belgicku, Holandsku, ale aj v Českej Republike či v Japonsku.</p>
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
