@extends('layouts.master')


@section('page', 'Lecturer Dashboard')

@section('breadcrumbs')
    <li>First Item</li>
    <li>/</li>
    <li>Second Item</li>
@endsection

@section('content')
    <x-button :route="route('home')">
        Go to Dashboard
    </x-button>
@endsection
