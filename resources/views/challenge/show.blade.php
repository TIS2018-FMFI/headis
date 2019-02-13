@extends('layouts.app')

@section('content')
    <vue-challenge :challenge="{{ json_encode($challenge) }}"
                   :current_user="{{ json_encode($currentAuthUser) }}"
                   :translations="{{ json_encode($translations) }}"
                   :time_zone="{{ json_encode(config('app.timezone')) }}"
                   :dates="{{ json_encode($dates) }}">
    </vue-challenge>
@endsection
