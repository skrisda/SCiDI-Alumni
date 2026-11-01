@extends('layouts.public')

@section('title', $title ?? 'ตั้งค่าบัญชี')

@push('appearance')
    @fluxAppearance
@endpush

@section('content')
    <x-account-workspace>{{ $slot }}</x-account-workspace>
    @persist('toast')
        <flux:toast.group><flux:toast /></flux:toast.group>
    @endpersist
    @fluxScripts
@endsection
