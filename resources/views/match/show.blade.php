@extends('layouts.app')

@section('content')

    <match-component :match="{{ json_encode($match) }}"
                     :finished="{{ json_encode($finished) }}"
                     :current_user="{{ json_encode($currentAuthUser) }}"

    ></match-component>

@endsection
