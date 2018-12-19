@extends('layouts.app')

@section('content')
    <vue-challenge :challenge="{{ json_encode($challenge) }}"
                   :current_user="{{ json_encode($currentAuthUser) }}">
    </vue-challenge>
@endsection