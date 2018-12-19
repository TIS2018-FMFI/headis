@extends('layouts.app')

@section('content')
    <vue-challenge :challenge="{{ json_encode($challenge) }}" :dates="{{ json_encode($dates) }}"
                   :comments="{{ json_encode($comments) }}" :challenger="{{ json_encode($challenger) }}"
                   :asked="{{ json_encode($asked) }}"></vue-challenge>
@endsection