@extends('layouts.app')

@section('content')
    <vue-manager :translations="{{ json_encode($translations) }}"
                 :can_deactivate_users="{{ json_encode($canDeactivateUsers) }}"
                 :can_reactivate_users="{{ json_encode($canReactivateUsers) }}"></vue-manager>
@endsection
