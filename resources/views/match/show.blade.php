@extends('layouts.app')

@section('content')

    <match-component :match="{{ json_encode($match) }}"></match-component>

@endsection
