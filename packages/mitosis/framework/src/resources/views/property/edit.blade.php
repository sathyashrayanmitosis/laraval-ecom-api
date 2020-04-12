@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $property->name }}
@stop

@section('content')
{!! Form::model($property, [
        'route'  => ['mitosis.property.update', $property],
        'method' => 'PUT'
    ])
!!}

    <div class="card card-accent-secondary">
        <div class="card-header">
            {{ __('Property Details') }}
        </div>

        <div class="card-block">
            @include('mitosis::property._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Save') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
