@extends('layouts.app')

@section('content')

    <match-component :match="{{ json_encode($match) }}"
                     :finished="{{ json_encode($finished) }}"
                     :current_user="{{ json_encode($currentAuthUser) }}"
                     :translations="{{ json_encode($translations) }}"
                     :can_add_sets="{{ json_encode($canAddSets) }}"
    ></match-component>

@endsection
