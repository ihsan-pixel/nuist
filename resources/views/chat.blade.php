@extends('layouts.master')

@section('title') @lang('translation.Chat') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Skote @endslot
@slot('title') Chat @endslot
@endcomponent

@livewire('chat')

@endsection
