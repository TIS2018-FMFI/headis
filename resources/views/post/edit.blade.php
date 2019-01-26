@extends('layouts.app')

@section('content')
    <edit-post :post="{{ json_encode($post) }}" :translations="{{ json_encode($translations) }}" :rte="{{ json_encode($rte) }}"></edit-post>
@endsection
