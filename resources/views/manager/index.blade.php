@extends('layouts.app')

@section('content')
    <vue-manager :translations="{{ json_encode($translations) }}"
                 :can_deactivate_users="{{ json_encode($canDeactivateUsers) }}"
                 :can_reactivate_users="{{ json_encode($canReactivateUsers) }}"
                 :season="{{ json_encode($season) }}"
                 :not_available_dates="{{ json_encode($notAvailableDates) }}"
                 :today="{{ json_encode(\Carbon\Carbon::today(config('app.timezone'))->toDateString()) }}"
                 :time_zone="{{ json_encode(config('app.timezone')) }}"
    ></vue-manager>
@endsection
